userList = ['A','B','C','D','E']
midCount = 60
assignedUser = {}
userCount = len(userList)
maxCount=0
for i in range(1,midCount):
    if userCount == maxCount:
        maxCount = 0
    assignedUser[i]=userList[maxCount]
    maxCount += 1
for key,value in assignedUser.items():
    print(f"key :{key}, value : {value}")
