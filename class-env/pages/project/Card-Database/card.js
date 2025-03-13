jQuery(function () {
    const format = $(document.getElementById("format"));
    const commanderGroup = $(document.getElementById("commanderGroup"));

    $(format).on("change", function () {
        console.log(this.value);
        if (this.value == "commander" || this.value == "oathbreaker" || this.value == "brawl" || this.value == "pauper_commander" || this.value == "brawl_historic") {
            commanderGroup.css("opacity", 1);
            commanderGroup.css("visibility", "visible");
        } else {
            commanderGroup.css("opacity", 0);
            commanderGroup.css("visibility", "hidden");
        }
    })

});