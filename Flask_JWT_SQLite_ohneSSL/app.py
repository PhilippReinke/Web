#
# source: https://geekflare.com/de/securing-flask-api-with-jwt/
#
from flask import Flask, request, jsonify, make_response
from flask_sqlalchemy import SQLAlchemy
from werkzeug.security import generate_password_hash, check_password_hash
import uuid
import jwt
import datetime
from functools import wraps

# prepare Flask
app = Flask(__name__)

# config data that may change
app.config["SECRET_KEY"] = "Th1s1ss3cr3t" # must be kept very secret
app.config["SQLALCHEMY_DATABASE_URI"] = "sqlite:////home/philipp/Schreibtisch/datenbank.db"
app.config["SQLALCHEMY_TRACK_MODIFICATIONS"] = True

# prepare database
db = SQLAlchemy(app)

class Users(db.Model):
	id = db.Column(db.Integer, primary_key=True)
	public_id = db.Column(db.Integer)
	name = db.Column(db.String(50))
	password = db.Column(db.String(50))
	admin = db.Column(db.Boolean)

class Authors(db.Model):
	id = db.Column(db.Integer, primary_key=True)
	name = db.Column(db.String(50), unique=True, nullable=False)
	book = db.Column(db.String(20), unique=True, nullable=False)
	country = db.Column(db.String(50), nullable=False)
	booker_prize = db.Column(db.Boolean)
	user_id = db.Column(db.Integer)

# decorator for JWT authentication
def token_required(f):
	@wraps(f)
	def decorator(*args, **kwargs):
		token = None

		if "x-access-tokens" in request.headers:
			token = request.headers["x-access-tokens"]

		if not token:
			return jsonify({"message": "a valid token is missing"})

		try:
			data = jwt.decode(token, app.config["SECRET_KEY"], algorithms="HS256")
			current_user = Users.query.filter_by(public_id=data["public_id"]).first()
		except:
			return jsonify({"message": "token is invalid"})
		
		return f(current_user, *args,  **kwargs)

	return decorator

# regsiter a user
@app.route("/register", methods=["GET", "POST"])
def signup_user():
	data = request.get_json()

	hashed_password = generate_password_hash(data["password"], method="sha256")

	new_user = Users(public_id=str(uuid.uuid4()), name=data["name"], password=hashed_password, admin=False)
	db.session.add(new_user)
	db.session.commit()

	return jsonify({"message": "registered successfully"})

# login a user
@app.route("/login", methods=["GET", "POST"])
def login_user():
	auth = request.authorization

	if not auth or not auth.username or not auth.password:  
		return make_response("could not verify", 401, {"WWW.Authentication": "Basic realm: 'login required'"})

	user = Users.query.filter_by(name=auth.username).first()
	 
	if check_password_hash(user.password, auth.password):
		token = jwt.encode({"public_id": user.public_id, "exp": datetime.datetime.utcnow() + datetime.timedelta(minutes=30)}, app.config["SECRET_KEY"], algorithm="HS256")
		return jsonify({"token" : token})

	return make_response("could not verify",  401, {"WWW.Authentication": "Basic realm: 'login required'"})

# list all users
@app.route("/user", methods=["GET"])
def get_all_users():
	users = Users.query.all()
	result = []

	for user in users:
		user_data = {}
		user_data["public_id"] = user.public_id
		user_data["name"] = user.name
		user_data["password"] = user.password
		user_data["admin"] = user.admin

		result.append(user_data)

	return jsonify({"users": result})

# list all authors
@app.route("/authors", methods=["GET", "POST"])
@token_required
def get_authors(current_user):
	authors = Authors.query.all()
	output = []

	for author in authors:
		author_data = {}
		author_data["name"] = author.name
		author_data["book"] = author.book
		author_data["country"] = author.country
		author_data["booker_prize"] = author.booker_prize
		output.append(author_data)

	return jsonify({"list_of_authors" : output})

# create author
@app.route("/new_author", methods=["POST", "GET"])
@token_required
def create_author(current_user):
	data = request.get_json()

	new_authors = Authors(name=data["name"], country=data["country"], book=data["book"], booker_prize=True, user_id=current_user.id)
	db.session.add(new_authors)
	db.session.commit()

	return jsonify({"message" : "new author created"})

# delete author
@app.route("/delete_author/<name>", methods=["DELETE"])
@token_required
def delete_author(current_user, name):
	# attention: name might appear multiple times
	# However, it is only deleted ones!
	author = Authors.query.filter_by(name=name, user_id=current_user.id).first()
	if not author:
		return jsonify({"message": "author does not exist"})   

	db.session.delete(author)
	db.session.commit()

	return jsonify({"message": "Author deleted"})

# run server
if __name__ == "__main__":
	app.run(debug=True)

"""
Beispiele mit POSTMAN (curl koennte man auch benutzen):

1 - Benutzer registrieren
http://127.0.0.1:5000/register
mit POST und JSON Daten
{"name":"Peter", "password":"1234"}

2 - alle Benutzer auflisten
http://127.0.0.1:5000/user
mit GET (also auch Firefox)

3 - Benutzer einloggen => JWT erzeugen
http://127.0.0.1:5000/login
unter GET und Authorisation und Basic Auth

4 - neuen Autor anlegen (Token notwendig!)
http://127.0.0.1:5000/new_author
unter Header folgendes einfuegen
KEY 				VALUE
Content-Type		application/json
x-access-tokens		<token>
und unter body raw/json
{"name":"Peter Müller", "book":"Avatar", "country":"Japan"}

5 - alle Autoren auslesen
http://127.0.0.1:5000/authors
unter Header folgendes einfuegen
KEY 				VALUE
Content-Type		application/json
x-access-tokens		<token>

6 - Autor loeschen
http://127.0.0.1:5000/delete_author/<name>
DELETE method mit Header
KEY 				VALUE
Content-Type		application/json
x-access-tokens		<token>

"""