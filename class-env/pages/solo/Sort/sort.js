// initialize the counter and the array
let namesSet = [];
let newname = $("#newname");
jQuery(function () {
    newname.on("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevent form submission
            $("#addname").trigger('click');
        }
    });

    $("#addname").on("click", function () {
        SortNames();
    });
});

function SortNames() {
    // Get the name from the text field
    let thename = $('#newname').val().trim();

    newname = styleName(thename);

    // Add the name to the array
    if (!namesSet.includes(thename)) {
        namesSet.push(thename);
        // Sort the array
        namesSet.sort();

        let generatedList = "";
        namesSet.forEach(name => {
            generatedList += ("<li>" + name + "</li>");
        });

        $("#sorted").html(generatedList);
    }

}

function styleName(name) {
    return name.charAt(0).toUpperCase() + name.slice(1);
}