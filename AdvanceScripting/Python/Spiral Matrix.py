# Script prints spiral matrix.

# the first line of input is the number of rows of the array
def matInit():
    n = int(input()) 
    a = []
    for i in range(n):
        a.append([str("0") for z in range(0,n)])
    return a

def spiral(mat):
    f1 = True
    change = -1
    trace = len(mat)
    row = 0
    column = 0
    filler = len(mat)*len(mat)
    for i in range(1,len(mat)*2):
        if f1:
            for j in range(0,trace):
                    #print("RIGHT")
                    #print(row,column)
                    mat[row][column] = filler
                    column = column + 1
                    filler = filler + change
            trace = trace -1
            column = column - 1
            for j in range(0,trace):
                    row = row + 1
                    #print("DOWN")
                    #print(row,column)
                    mat[row][column] = filler
                    filler = filler + change
        else:
            for j in range(0,trace):
                    column = column - 1
                    #print("LEFT")
                    #print(row,column)
                    mat[row][column] = filler
                    filler = filler + change
            trace = trace -1
            for j in range(0,trace):
                    row = row - 1
                    #print("UP")
                    #print(row,column)
                    mat[row][column] = filler
                    filler = filler + change
            column = column + 1
        f1 = not f1
    return mat

matrix = matInit()
finalOutput = spiral(matrix)

for i in finalOutput:
    print(i)



