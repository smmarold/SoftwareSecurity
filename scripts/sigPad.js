/**************************************************************************************************************************************************************
 * 
 * Signature Pad functionality. The main JavaScript for the pads is here, but the save functions and conversions to Base64 was moved to each individual page
 * Mainly because each page required a slightly different functionality when saving, and the signatures are stored in page-relevant tables. 
 * This Code is a mixture of my own sig pad code, and code that Jay Aguiar showed us to get the sig pads to work. 
 * 
 **************************************************************************************************************************************************************/

//Query Selectors
let c = document.querySelectorAll(`canvas`);
let s = document.querySelectorAll(`.saveButtons`);
let blobInput = document.querySelector(`#blobData`);
let blobInputTwo = document.querySelector(`#blobDataTwo`);

//Initialize Vars
let mouse ={x:0, y:0};
let drawing = false;

//Add event listeners for mouse controls and touch controls to the Canvas elements (loops because there are two elements)
for(let i=0; i<c.length; i++)
{
    if(c[i]){
        c[i].addEventListener(`mousemove`, moveM)
        c[i].addEventListener(`touchmove`, moveT)
        c[i].addEventListener(`mousedown`, draw); 
        c[i].addEventListener(`touchstart`, draw); 
        c[i].addEventListener(`mouseup`, end); 
        c[i].addEventListener(`touchend`, end); 
    }
}

//Track mouse movement
function moveM(evt)
{
    let tempC = evt.target
    var rect = tempC.getBoundingClientRect();
    let ctx = tempC.getContext(`2d`);
    mouse = {x:evt.clientX - rect.left, y:evt.clientY - rect.top}

    //Draw the line if drawing bool is true
    if(drawing)
    {
        ctx.lineStyle = `black`;
        ctx.lineWidth = 2;
        
        ctx.lineTo(mouse.x, mouse.y)
        ctx.stroke();
    }
    

}

//Track Touch Movement
function moveT(evt)
{
    let tempC = evt.target
    var rect = tempC.getBoundingClientRect();
    let ctx = tempC.getContext(`2d`);
    mouse.x = evt.targetTouches[0].clientX - rect.left
    mouse.y = evt.targetTouches[0].clientY - rect.top
    console.log(evt.targetTouches)

    //Draw the line if drawing bool is true
    if(drawing) 
    {
        ctx.lineStyle = `black`;
        ctx.lineWidth = 2;
        
        ctx.lineTo(mouse.x, mouse.y)
        ctx.stroke();
    }
    

}

//called while mouse is down, or screen is touched. 
function draw(evt)
{ 
        let tempC = evt.target
        var rect = tempC.getBoundingClientRect();
        let ctx = tempC.getContext(`2d`);
        if(evt.targetTouches)
        {
            let rect = tempC.getBoundingClientRect();
            mouse.x = evt.targetTouches[0].clientX - rect.left
            mouse.y = evt.targetTouches[0].clientY - rect.top
        }
            drawing = true;
            ctx.moveTo(mouse.x, mouse.y)
            ctx.save();
            ctx.beginPath();

}

//Ends the lin on mouseup or touchup
function end(evt)
{    
    let tempC = evt.target
    var rect = tempC.getBoundingClientRect();
    let ctx = tempC.getContext(`2d`);
    drawing = false;
    ctx.closePath();
    ctx.restore();           
}

//Didn't end up using these, but leaving them as they will likely come in handy in the future. 
//Converts base64 string to BLOB
async function convertToBlob(bas64){
    const newData = await fetch(bas64);
    const blob =  await newData.blob();

    return blob;
}

//Converts a blob back to base 64
function blobToBase64(blob){
    const reader = new FileReader();
    reader.readAsDataURL(blob);

    return new Promise(resolve => {
        reader.onloadend = () => {
            resolve(reader.result);
        }
    })
}
