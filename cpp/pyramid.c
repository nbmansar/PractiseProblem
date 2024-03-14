#include<stdio.h>
#include<string.h>
int main(){
	char duplicat[100] = " ";
	char word[100] = "geeks for geeks";
	int dlen = strlen(duplicat);
	int wlen = strlen(word);
	for(int i=wlen;i>=0;i--){
	  int isPush = 1;
		for(int j=0;j<=dlen;j++){
			if(duplicat[j] != word[i]){
				duplicat[dlen]=word[i];
				duplicat[dlen+1]='\0';
				dlen = strlen(duplicat);
			}else{
				isPush = 0;
				break;

			}
		}
		if(isPush == 1){
		printf("%c",word[i]);
		}	
	}
	return 0;
}
