#!/user/bin/ruby

# Single line edgedetector

def edgeDetector(input)
	output = "0"

	# In built function each_cons for array, arguement 2 is passed that gets pair of 2 per iteration 
	input.each_cons(2) do |pair|
		if pair[0] != pair[1]
			output << "1"	
		else
			output << "0"
		end
	end
	return output
end	

input_line = gets

if input_line != ""
	input_line = input_line.strip
	output_line = edgeDetector(input_line.split(""))
	print "Input:     "
	puts "#{input_line}"
	print "Output:    "
	puts "#{output_line}"
end

