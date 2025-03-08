// Sean Slaughter
// Date: 2/20/25
// CPSC 3750
// Programming Exam #1
// Grade level you completed: Senior

let cachePrime = new Set(); // holds all UNIQUE prime numbers calculated this session
let cacheComposite = new Set();// same as above but with composite
let highestCalc = 1;
let is_Descending = false;
let currCompSum = 0;
let currPrimeSum = 0;

addEventListener("DOMContentLoaded", () => {

    document.getElementById("startButton").addEventListener("click", generateLists);
    document.getElementById("themeToggle").addEventListener("click", toggleDark);
    document.getElementById("sortLists").addEventListener("click", function () {
        sortLists();
        generateLists();
    });

    function toggleDark() {
        let body = document.getElementsByTagName("body")[0];
        body.classList.toggle("darkMode");

    }


    function isPrime(n) {
        if (cacheComposite.has(n)) {
            return false
        }
        if (cachePrime.has(n)) {
            return true;
        }
        if (n === 1) {
            cacheComposite.add(n);
            return false;
        }

        let tempCount = 0;
        for (let i = 2; i * i <= n; i++) {
            if (n % i === 0) {
                tempCount++;
                break;
            }
        }
        if (tempCount === 0) {
            cachePrime.add(n);
            return true;
        } else {
            cacheComposite.add(n);
            return false;
        }


    }

    function makePrimeList(max) {
        let primeList = document.getElementById("primeList");
        primeList.innerHTML = ""; // Reset on new list
        currPrimeSum = 0;
        for (const value of cachePrime) {
            if (value <= max) {
                let element = document.createElement("li");
                element.innerText = value;
                currPrimeSum += parseInt(value);
                primeList.appendChild(element);
            }
        }
    }

    function makeCompList(max) {
        let compList = document.getElementById("compList");
        compList.innerHTML = ""; // Reset on new list
        currCompSum = 0;
        for (const value of cacheComposite) {
            if (value <= max) {
                let element = document.createElement("li");
                element.innerText = value;
                element.value = value;
                currCompSum += parseInt(value);
                element.addEventListener("mouseenter", showDivisors);
                element.addEventListener("mouseleave", hideDivisors);
                compList.appendChild(element);
            }
        }
    }

    function showDivisors(event) {

        let num = parseInt(event.target.value);

        let divisorElement = document.createElement("ul");
        divisorElement.classList.add("divisor");
        for (let i = 0; i <= num; i++) {
            if (num % i === 0) {
                let temp = document.createElement("li");
                temp.innerText = i;
                temp.removeEventListener("mouseover", showDivisors);
                divisorElement.appendChild(temp);


            }
        }
        event.target.appendChild(divisorElement);


    }

    function hideDivisors(event) {

        event.target.innerHTML = event.target.value;
    }

    function generateLists() {

        let val = document.getElementById("numberInput").value;

        if (val < 1) {
            alert("Please enter a positive number, greater than 0");
            return;
        }
        // noinspection EqualityComparisonWithCoercionJS
        if (val != Math.floor(val)) // Check if it is a float or not
        {
            alert("Please enter a whole integer (ex. 1, 5, 100). Check: " + val + " not equal to " + parseInt(val));
            return;
        }

        //Check if number is prime (this checks all smaller numbers too)
        if (highestCalc < val) {
            for (let i = highestCalc; i <= val; i++) {
                isPrime(i);
            }

            highestCalc = val;

            sortLists()

            //Make each list separately
            makePrimeList(val);
            makeCompList(val);
        }
        //Make each list separately 
        // WITHOUT sorting or calculating numbers (the "cache"
        makePrimeList(val);
        makeCompList(val);
    }

    function sortLists() {
        if (is_Descending) {
            // Sort Sets to Keep the Unique Property, also maintain it's sorted nature
            cachePrime = new Set(Array.from(cachePrime).sort(function (a, b) {
                return b - a
            }));
            cacheComposite = new Set(Array.from(cacheComposite).sort(function (a, b) {
                return b - a
            }));
            is_Descending = !is_Descending; // it is now Ascending
        } else {
            // Sort Sets to Keep the Unique Property, also maintain it's sorted nature
            cachePrime = new Set(Array.from(cachePrime).sort(function (a, b) {
                return a - b
            }));
            cacheComposite = new Set(Array.from(cacheComposite).sort(function (a, b) {
                return a - b
            }));
            is_Descending = !is_Descending; // it is now Descending
        }
    }


    document.getElementById("primeSum").addEventListener("click", function (event) {
        console.log(currPrimeSum);
        event.target.innerText = currPrimeSum;
    });
    document.getElementById("compSum").addEventListener("click", function (event) {
        event.target.innerText = currCompSum;
    });

});

