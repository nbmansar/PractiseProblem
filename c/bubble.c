#include<stdio.h>
#include<string.h>

int main(){
int a[] = {7,6,8,4,9,12,1};
int ascTemp = 0;
int descTemp = 0;
int size = sizeof(a)/sizeof(a[0]);
int b[size];
for(int i=0;i<=size-1;i++){
   for(int j=0;j<=size-1;j++){
	if(a[j] > a[j+1]){
		ascTemp = a[j];
		a[j] = a[j+1];
		a[j+1] = ascTemp;
	}
   }
}
for(int i=1;i<=size;i++){
	printf("%d",a[i]);
}		
return 0;
}
