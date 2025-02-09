// initialize the counter and the array
var numbernames = 0;
var names = new Array();

window.onload = function() {
    document.getElementById("newname").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevent form submission
            document.getElementById("addname").click();
        }
    });
};


function SortNames() {
    // Get the name from the text field
    let thename = document.getElementById("newname").value;
    
    thename = styleName(thename);
    
    
    // Add the name to the array
    names[numbernames] = thename;
    // Increment the counter
    numbernames++;
    // Sort the array
    names.sort();
    
    let generatedList = "";
    names.forEach(name => {
        generatedList += ("<li>" + name + "</li>");
        
    })
    document.getElementById("sorted").innerHTML = generatedList;
}


function styleName(name)
{
    return name.charAt(0).toUpperCase() + name.slice(1);
}



