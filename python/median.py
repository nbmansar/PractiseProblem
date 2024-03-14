def getMedianValue(marks):
    marksCount = len(marks)
    temp =0
    for j in range(marksCount):
        for i in range(0,marksCount-j-1):
            if marks[i] > marks[i+1]:
                marks[i],marks[i+1] = marks[i+1],marks[i]

    print("Sorting without in build Function:", marks)

    if marksCount % 2 == 0:
        firstIndex = (marksCount // 2) - 1
        secondIndex = marksCount // 2
        median = (marks[firstIndex] + marks[secondIndex]) / 2
    else:
        median = marks[marksCount // 2]

    return float(median)

print("Median Value is:", getMedianValue([35, 50, 40, 10, 20])) 
print("Median Value is:", getMedianValue([30, 50, 45, 10, 20, 60]))  
