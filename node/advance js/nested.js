let a = 10
console.log(a);

function outer(){
    let a=20
    console.log(a);
    function inner(){
        let c=30
        console.log(c);
    }
    inner()
}

outer()