"""
	Simple Flask Backend.
"""
from flask import Flask, request, send_from_directory, redirect
#from flask_cors import CORS
from werkzeug.utils import secure_filename
import os
import BackgroundRemover as bg

# instantiate the app and load coniguration
app  = Flask(__name__)
app.config.from_pyfile("config.py")
# enable CORS
#CORS(app, resources={r"/*": {"origins":app.config["CORS_ORIGINS"]}})

# instantiate remover and with it
# load ML model
remover = bg.BackgroundRemover()

# only allow https (allways redirect)
@app.before_request
def before_request():
	if not request.is_secure:
		url = request.url.replace('http://', 'https://', 1)
		code = 301
		return redirect(url, code=code)

# check if stream is indeed an image
import imghdr
def validate_image(stream):
	header = stream.read(512)
	stream.seek(0)
	format = imghdr.what(None, header)
	if not format:
		return None
	return "." + (format if format != "jpeg" else "jpg")

# covert image to sw
from PIL import Image
def removeBackground(path):
	try:
		remover.apply(path)
	except:
		return 1
	return 0

# payloads size exceeds MAX_CONTENT_LENGTH
@app.errorhandler(413)
def too_large(e):
	return "Payload is too large", 413

# accept multiple files for uploading
@app.route("/", methods=["POST"])
def upload_files():
	"""
		Since we are handling multiple files at the same time
		we return the status for each file separately
		{"status":"ok / error", "message":"Link2ConvertedFile / Reason"}
		reasons: file not an image, ... 
	"""
	status = {}
	readyForProcessing = []
	# iterate over sended files and save them
	for filename in list(request.files):
		# make filename secure
		file = request.files[filename]
		filename = secure_filename(file.filename)
		# Has a file been selected? (ignore if not)
		if filename != "":
			# check file size
			file.seek(0, os.SEEK_END)
			if file.tell() > app.config["MAX_IMG_SIZE"]:
				status[filename] = {"status":"error", "message":"Datei ist zu groß."}
				continue
			file.seek(0)
			# Is file an image?
			file_ext = os.path.splitext(filename)[1]
			file_ext = ".jpg" if file_ext == ".jpeg" else file_ext
			if file_ext not in app.config["ALLOWED_EXTENSIONS"] or file_ext != validate_image(file.stream):
				status[filename] = {"status":"error", "message":"Dateiformat ist nicht zulässig."}
				continue
			# save file
			temp_path = os.path.join(app.config["UPLOAD_PATH"], filename)
			file.save(temp_path)
			readyForProcessing.append({"filename":filename, "path":temp_path})
	# remove background
	for ele in readyForProcessing:
		if removeBackground(ele["path"]):
			# ERROR
			status[ele["filename"]] = {"status":"error", "message":"Fehler bei der Umwandlung."}
		else:
			# SUCCESS
			idxLastDot = ele["filename"].rfind(".")
			downloadLink = request.base_url+"uploads/"+ele["filename"][:idxLastDot]+str("_bg.png")
			status[ele["filename"]] = {"status":"ok", "message":downloadLink}
	return status, 200

# provide images from folder uploads
@app.route("/uploads/<filename>")
def upload(filename):
	if os.path.isfile(app.config["UPLOAD_PATH"]+"/"+filename):
		return send_from_directory(app.config["UPLOAD_PATH"], filename), 200
	return "File does not exist", 404

# serve frontend
#
## Achtung: Only temporarily. Use Apache2 or nginx to serve static content.
#
@app.route("/")
def index():
	return send_from_directory(app.config["FRONTEND_PATH"], "index.html"), 200
@app.route("/<path:path>")
def serve_ressource(path):
	return send_from_directory(app.config["FRONTEND_PATH"], path), 200