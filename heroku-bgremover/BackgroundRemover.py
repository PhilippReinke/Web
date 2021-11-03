from tflite_runtime.interpreter import Interpreter
from PIL import Image, ImageFilter
import numpy as np

class BackgroundRemover:

	def __init__(self):
		self.interpreter = Interpreter(model_path="mobilenetv2_coco.tflite")
		self.interpreter.allocate_tensors()

		# get information about model
		self.input_details = self.interpreter.get_input_details()
		self.output_details = self.interpreter.get_output_details()
		_, self.height, self.width, _ = self.input_details[0]["shape"]
		_, self.height_out, self.width_out, self.c_out = self.output_details[0]["shape"]

	def apply(self, pathImg):

		# load image
		im = Image.open(pathImg)
		res_im = im.resize((self.width, self.height))
		idxLastDot 	 = pathImg.rfind(".")
		filename_new = pathImg[:idxLastDot]+str("_bg.png")

		#
		np_res_im = np.array(res_im)
		if self.input_details[0]["dtype"] == np.uint8:
			np_res_im = (np_res_im/255).astype("uint8")
		else:
			np_res_im = (np_res_im/255).astype("float32")

		# predict
		if len(np_res_im.shape) == 3:
			np_res_im = np.expand_dims(np_res_im, 0)
		input_shape = self.input_details[0]["shape"]
		input_data = np_res_im
		self.interpreter.set_tensor(self.input_details[0]["index"], input_data)
		self.interpreter.invoke()
		output_data = self.interpreter.get_tensor(self.output_details[0]["index"])

		# If the model is quantized (uint8 data), then dequantize the results
		if self.output_details[0]["dtype"] == np.uint8:
			scale, zero_point = self.output_details[0]["quantization"]
			output_data = scale * (output_data - zero_point)

		#
		mSegmentBits = np.zeros((self.height_out, self.width_out)).astype(int)
		outputbitmap = np.zeros((self.height_out, self.width_out)).astype(int)
		for y in range(self.height_out):
			for x in range(self.width_out):
				maxval = 0
				mSegmentBits[y][x] = 0
				for c in range(self.c_out):
					value = output_data[0][y][x][c]
					if c == 0 or value > maxVal:
						maxVal = value
						mSegmentBits[y][x] = c
				if(mSegmentBits[y][x]==15): # 15 = person
					outputbitmap[y][x]=1
				else:
					outputbitmap[y][x]=0
		temp_outputbitmap= outputbitmap*255
		PIL_image = Image.fromarray(np.uint8(temp_outputbitmap)).convert("L")
		org_mask_img = PIL_image.resize(im.size)

		# smooth edges
		org_mask_img = org_mask_img.filter(ImageFilter.ModeFilter(size=35))
		
		# remove background and save image
		im.putalpha(org_mask_img)
		im.save(filename_new)