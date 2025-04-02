// JavaScript to filter grades based on the selected semester
function filterGrades() {
    var semester = document.getElementById("semesterFilter").value;
    
    var table = document.getElementById("gradesTable");
    var rows = table.getElementsByTagName("tr");

    // Loop through table rows and hide those that don't match the selected semester
    for (var i = 1; i < rows.length; i++) {
        var cell = rows[i].getElementsByTagName("td")[5]; // Column 6 (Semester column)
        if (cell) {
            var semesterText = cell.textContent || cell.innerText;
            if (semester === "" || semesterText === semester) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
}