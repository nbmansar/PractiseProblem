#include<stdio.h>

int main(){
	char a[5] = "hello";
	int len = strlen(a)
	for(int i=0;i<=len;i++){
		for(int j=0;j<=i;j++){
			printf("%d",a[j]);
		}
	}
	return 0;
}
