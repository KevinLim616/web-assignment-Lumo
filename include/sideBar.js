const popButton = document.querySelector(".logout-pop");
const popover = document.querySelector(".popover");

popButton.addEventListener("click", () => {
  popover.classList.toggle("active");
});

document.addEventListener("click", (event) => {
  if (!popover.contains(event.target) && !popButton.contains(event.target)) {
    popover.classList.remove("active");
  }
});
