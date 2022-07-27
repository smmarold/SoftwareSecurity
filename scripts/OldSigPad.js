//Script for the signature pad. 

//grabbing/storing our elements. 
const canvas = document.querySelector('canvas');
const form = document.querySelector('form');
const clearButton = document.querySelector('.clear-button');
const ctx = canvas.getContext('2d');
let writingMode = false;

//canvas ctx properties
ctx.lineWidth = 3;
ctx.lineJoin = ctx.lineCap = 'round';

//Define handler functions
const handlePointerDown = (e) => {
    writingMode = true;
    ctx.beginPath();
    const [positionX, positionY] = getCursorPosition(e);
    ctx.moveTo(positionX, positionY);
}

const handlePointerUp = () => {
    writingMode = false;
}

const handlePointerMove = (e) => {
    if(!writingMode) return

    const [positionX, positionY] = getCursorPosition(e);
    ctx.lineTo(positionX, positionY);
    ctx.stroke();
}

//Getting the cursor position
const getCursorPosition = (e) => {

    positionX = e.clientX - e.target.getBoundingClientRect().x;
    positionY = e.clientY - e.target.getBoundingClientRect().y;

    return [positionX, positionY];
}

//Clear the pad
const clearPad = () => {
    ctx.clearRect(0,0, canvas.width, canvas.height);
}

//When we eventually submit the form, I will add code here to save the signature (if it's filled), 
// and return what was saved so we can store it in the database. For now, just console.logging. 

form.addEventListener('submit', (e) => {
    e.preventDefault();
    console.log('Signature Submitted.')
})

//Event listeners for "pointers", setting writing mode, and drawing, as well as for clear button. 
canvas.addEventListener('pointerdown', handlePointerDown, {passive:true});
canvas.addEventListener('pointerup', handlePointerUp, {passive:true});
canvas.addEventListener('pointermove', handlePointerMove, {passive:true});
clearButton.addEventListener('click', (e) => {
    e.preventDefault();
    clearPad();
})

if(`<?= (string)$componentValues["userID_Sig1"] ?>` != ``)
sigCheck1 = `<?= (string)$componentValues["userID_Sig1"]; ?>`;
if(`<?= (string)$componentValues["userID_Sig2"] ?>` != ``)
sigCheck2 = `<?= (string)$componentValues["userID_Sig2"]; ?>`;