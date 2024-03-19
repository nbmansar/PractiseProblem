#include<stdio.h>

int main(){
	int a[10]= {9,10,4,5,6};
	int len =sizeof(a)/sizeof(a[0]);int currentValue = 0;int max=0;

	for(int i=0;i<=len;i++){
		
		if(i+2 < len){
		for(int j=i;j<=i+2;j++){
			currentValue += a[j];
		}
		if(currentValue > max){
			max = currentValue;
		}
		currentValue = 0;
		}else{
			break;
		}
	}
	printf("%d",max);
return 0;
}
