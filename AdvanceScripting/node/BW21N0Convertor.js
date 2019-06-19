
//---------------------------------------------------------------------------------------------------------------													-
// A NodeJS script that converts a black and white (monochrome) PNG image into an array of 1s and 0s. 	-
// A 1 should represent a black pixel and a 0 should represent a white pixel.					-
// The array should then be printed to standard output with each row separated by a line break			-
//---------------------------------------------------------------------------------------------------------------
	


// Using jimp, file system, read chunk and file type modules 
var jimp = require("jimp");
var fs = require("fs");
const readChunk = require("read-chunk");
const filetype = require("file-type");

// checking total number of arguments  
if(process.argv.length<=2)
{		
	console.log("Too few arguements");
	process.exit();
}
else
{
	f1 = process.argv[2];
}

fs.stat(f1,function(err,stat){
	if(err == null)
	{
		// getting magic number to check file type
		const buffer = readChunk.sync(f1,0,4100,function (err)
		{
			if(err)
			{			
				console.log("File does not exists");
				process.exit();
			}
		});
		var binary = "";
		// checks file type, if it is an image then program goes forward otherwise it halts
		if(filetype(buffer).mime.indexOf('image/') > -1)
		{
			if(err)
			{
				console.log("File does not exits");			
			}
			else
			{
			// Reads file 
			jimp.read(f1, function (err, image){
				image.scan(0, 0, image.bitmap.width, image.bitmap.height, function (x, y, idx) {
	    				// Setting up RGB values idx is an offset
					var red   = this.bitmap.data[ idx + 0];
					var green = this.bitmap.data[ idx + 1];	
					var blue  = this.bitmap.data[ idx + 2];
	    				
					// Checks whether pixel is black
					if ((red == 0) && (green == 0) && (blue == 0))
					{
	   				 	binary += "1";
					}
					// Checks whether pixel is white
	   				else if((red == 255) && (green == 255) && (blue == 255))
					{
						binary += "0";
	   				}
					// Cheks whether pixel is other than white and black
					else
					{
						console.log("File provided is not black and white / Monochrome");
						process.exit();			
					}
					// Checks total witdh of image against x to print newline
					if(x==image.bitmap.width-1)
					{
						binary += "\n";	
					}
				});
				// if 2nd arguement exists then it saves file using arguement otherwise prints output
				if(process.argv[3]!=null)
				{
					fs.writeFile(process.argv[3],binary, function(err){});
				}
				else
				{
					console.log(binary);
				}			
			});
			}
		}
		else
		{
			console.log("File is not Image");
		}
	}
	else
	{
	console.log("File does not exists");
	}
});