const boxes = document.querySelectorAll(".box");
const dragItem = document.querySelector("#drag-item");

boxes.forEach(box => {
    box.addEventListener("dragover", event => {
        if (box.contains(dragItem)) return;
        event.preventDefault();
    });

    box.addEventListener("drop", event => {
        event.preventDefault();

        if (box.contains(dragItem)) return;
        box.style.border = "1px solid black";
        box.appendChild(dragItem);
    });

    box.addEventListener("dragenter", () => {
        if (box.contains(dragItem)) return;
        box.style.border = "5px solid blue";
    });

    box.addEventListener("dragleave", () => {
        if (box.contains(dragItem)) return;
        box.style.border = "1px solid black";
    });
});

dragItem.addEventListener("dragstart", event => {
    dragItem.style.opacity = 0.2;
    event.dataTransfer.effectAllowed = "move";
});

dragItem.addEventListener("dragend", () => {
    dragItem.style.opacity = 1;
});
