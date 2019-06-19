#!/user/bin/ruby

#Function to check input lines length and its content should be 1's and 0's
def inputCheck(input_txt,total_lines,line_length)
	for i in 1..total_lines-1
		# checks each lines length with first line length and uses regex to check lines contains only 0's n 1's
		if (input_txt[i].length != line_length) && (/^[0-1]+$/ != input_txt[i])
			return 1
		end
	end
	return 0 
end

def edgeDetect( input_txt, total_lines, line_length)
	output = "0"
	# For first line of input same mechanisam of simple edgedetector, this time with loop
	for i in 1..line_length-1
		if input_txt[0][i-1] != input_txt[0][i]
			output << "1"
		else
			output << "0"
		end
	end
	output << "\n"

	# For rest of lines of input it has two for loops one for total lines and another to check each bit
	for i in 1..total_lines-1
		for j in 0..line_length-1
			# this line is only for first bit and it checks bit which is above it for changes
			if (j==0)
				if (input_txt[i][j] != input_txt[i-1][j])
					output << "1"
				else
					output << "0"
				end
			else
				# this line check bit before and above with the positioned bit
				if (input_txt[i][j-1] != input_txt[i][j]) || (input_txt[i-1][j] != input_txt[i][j])
					output << "1"
				else
					output << "0"
				end	
			end
		end	
		output << "\n"
	end
	return output
end 

# this function prints results
def displayResult(input_lines,output_lines)
	puts "Input:	 "
	for i in 0..input_lines.length-1
		puts  "#{input_lines[i]}"
	end
	puts "Output:	 "
	puts  "#{output_lines}"
end

#core function with controls program procedures
def main()
	# it gets all lines until it gets newline with space
	$/ = "\n "
	input = STDIN.gets
	if input !="" && input != nil
		input = input.split
		total_length =  input.length
		line_length = input[0].length
		if inputCheck(input, total_length,line_length)
			output = edgeDetect(input,total_length,line_length)
			displayResult(input,output)
		else
			puts "invalid input"
		end
	end
end

# this calls main function
main()
