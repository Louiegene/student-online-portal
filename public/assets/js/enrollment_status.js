const infoStatus = document.getElementById("info-status");
const statusValue = "Enrolled"; // Replace with dynamic value if needed
let badgeClass = '';

switch (statusValue) {
  case "Enrolled":
    badgeClass = 'enrolled';
    break;
  case "Dropped":
    badgeClass = 'dropped';
    break;
  case "No Longer Participating (NLP)":
    badgeClass = 'nlp';
    break;
  default:
    badgeClass = 'unknown';
}

if (infoStatus) {
  infoStatus.innerHTML = `<span class="status-badge ${badgeClass}">${statusValue}</span>`;
}
