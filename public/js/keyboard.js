// Offcanvas
const offcanvas = new bootstrap.Offcanvas('#offcanvas', { backdrop: false, scroll: true })

let inputElId = 'search';

// Setting Input Focus
function setFocus(elId) {
  inputElId = elId;
  document.getElementById(elId).focus();
}

// Displaying values
function display(val) {
  let input = document.getElementById(inputElId);

  input.value += val;
  @this.set(inputElId, input.value);
}

// Clearing the display
function clearDisplay() {
  let inputSearch = document.getElementById(inputElId);
  inputSearch.value = inputSearch.value.substr(0, inputSearch.value.length - 1);
  @this.set(inputElId, inputSearch.value);
}