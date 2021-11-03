""" Flask configuration. """
import os

# custom settings
#CORS_ORIGINS 		= "*"
ALLOWED_EXTENSIONS 	= [".jpg", ".png"]
UPLOAD_PATH 		= os.getcwd() + "/uploads"
FRONTEND_PATH		= os.getcwd() + "/frontend"
MAX_IMG_SIZE		= 2 * 1000 * 1000 # 2 MB

# flask settings
# maximal allowed payload 20 MB
MAX_CONTENT_LENGTH	= 50 * 1000 * 1000