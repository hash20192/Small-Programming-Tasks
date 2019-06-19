// Image Convertor to MONO

var gm = require("gm").subClass({"imageMagick" : true});

// splicing  arguements 
var  args = process.argv.slice(2);

gm(args[0]).monochrome().write(args[1], function(err){
	if(err) throw err;
	console.log("Conversion Done Successfully.....");
});
