function memoizedSquare() {
    const cache = {};
  
    return function(n) {
      if (cache[n]) {
        console.log(`Retrieving square of ${n} from cache`);
        return cache[n];
      } else {
        console.log(`Calculating square of ${n}`);
        const result = n * n;
        cache[n] = result;
        return result;
      }
    };
  }
  
  const square = memoizedSquare();
  
  console.log(square(5)); // Output: Calculating square of 5, 25
  console.log(square(5)); // Output: Retrieving square of 5 from cache, 25
  