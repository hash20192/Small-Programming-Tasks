#!/usr/bin/perl -T

# Trim subroutine
sub trim{my $s = shift; $s =~ s/^\s+|\s+$//g; return $s};

# Enrolment Mode selection subroutine
sub EnrolmentMode {
	my @uni = split / / ,shift;
		foreach $unit (@uni){
		# regx finds first characters then 5 then rest of digits
		if($unit =~ m/^\D*[5]\d*/g){
		return "Postgraduate";
		}
		# regx finds first characters then 6 then rest of digits		
		elsif($unit =~ m/^\D*[6]\d*/g){
		return "Postgraduate";
		}
		else{
		return "Undergraduate";
		}
	}
};

# Student Type subroutine checks if undergrad student has more than 2 units then it sets full time oterwise part time
# if postgrad student has units less than 2 then it sets part time otherwise full time
sub StudentType {
	my @st = split / / ,shift;
	$check = shift;
	print $check;
	if((scalar @st >= 3 && $check eq "Undergraduate") || (scalar @st >= 2 && $check eq "Postgraduate")){
		return "Full Time"	
	}
	else{
		return "Part Time";	
	}
};

# Display data subroutine shows student data
sub DisplayData{
	my @fields = split /,/ ,shift;
	print "Name: @fields[0]\n";
	print "Student#: @fields[1]\n";
	print "Email: @fields[2]\n";
	$units = trim(@fields[3]);
	$eMode = EnrolmentMode($units);
	print "Units Enrolled: $units\n";
	print "Enrolment Mode: ",$eMode,"\n";
	print "Student Type: ",StudentType($units,$eMode),"\n\n";
};


# checks arguements if not given throws error
if($ARGV[0]){
#opening file
open(Data, "<".$ARGV[0]) or die "Couldn't open specified file, $! ";
my @data ;

while (<Data>){
	@fields = split /,/ , $_;
	# if data doesn't have comma seperated fields then it will throw error
	if(scalar @fields <= 3){
		print "Input provided is not comma separated value formatted spreadsheet\n";	
		exit 0;	
	}
	else{
	# checks no field should be blank if it is then it throws error
		foreach $check (@fields){
				if($check eq ''){
					print "Values are missing in a field of the spreadsheet\n";		
					exit 0;		
				}
		}
	
	push @data, $_;
		
	}
	
}

foreach $record (@data){
DisplayData($record);
}
# closing file
close(Data) || die "Couldn't close file properly";
}
else{
print "No arguement provided\n";
}