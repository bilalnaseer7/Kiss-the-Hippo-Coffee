//DOM Queries 
const startscreen = document.getElementById('startscreen');
const playscreen = document.getElementById('playscreen');
const gameoverscreen  = document.getElementById('gameoverscreen');
const playbtn = document.getElementById('playbtn');
const restartbtn = document.getElementById('restartbtn');
const showtimer = document.getElementById('showtimer'); 

const difficulty = document.getElementById('difficulty');
const gamegrid = document.getElementById('gamegrid');

const yourscore = document.getElementById('yourscore');
const bestscore = document.getElementById('bestscore');

const board = document.getElementById('board');
const boardform = document.getElementById('boardform');
const enteredname = document.getElementById('name');

//Variables 
let mugs = []; 
let activegame = false;
let randIndex = null; 
let score = 0;
let best = 0; 
let topthreescore = []; 
let time = 1000;
let base = 1000;
let timer = null;
let counttimer = null; 

const topthree = localStorage.getItem('topthreescore'); 
if (topthree){
    topthreescore = JSON.parse(topthree); 
}
leaderBoard(); 

//Leaderboard 
function leaderBoard(){
    if (!topthreescore.length){
        topthreescore = []; 
    }
    
    board.innerHTML = '';

    topthreescore.forEach(i => {
        const li = document.createElement('li');
        li.textContent = `${i.name} - ${i.score}`;
        board.appendChild(li); 
    })

    if (topthreescore.length > 0){
        best = topthreescore[0].score;
        bestscore.textContent = 'Best score: ' + best;
    }
    else{
        bestscore.textContent = ''; 
    }
}

//Screens 
function screenDisplay(display){
    startscreen.classList.add('hidden');
    playscreen.classList.add('hidden');
    gameoverscreen.classList.add('hidden');

    if (display === 'start'){
        startscreen.classList.remove('hidden');
    }
    else if (display === 'play'){
        playscreen.classList.remove('hidden');
    }
    else if (display === 'over'){
        gameoverscreen.classList.remove('hidden'); 
    }
}

//Set up the game grid 
function gameGrid(){

    gamegrid.innerHTML = '';
    mugs = [];

    for (let i = 0; i < 16; i++){
        const mugdiv = document.createElement('div');
        mugdiv.className = 'mug';
        mugdiv.index = i;
    

        const img = document.createElement('img');
        img.className = 'mug-img';
        img.src = 'images/mug.png'; 
        img.alt = 'Red coffee mug'; 

        mugdiv.appendChild(img);
        mugdiv.addEventListener('click', clickMug)

        gamegrid.appendChild(mugdiv);
        mugs.push(mugdiv); 
    }
}

//Reset game 
function resetMug(){
    mugs.forEach(mug =>{
        const img = mug.querySelector('.mug-img');
        if (img){
            img.src = 'images/mug.png'; 
            img.classList.remove('blink'); 
        }
    })
}

//Play game 
function playGame(){
    activegame = true;
    score = 0;

    clearTimeout(timer);
    resetMug(); 

    if (difficulty.value === 'easy'){
        time = 7000;
    }
    else if (difficulty.value === 'medium'){
        time = 5000; 
    }
    else if (difficulty.value === 'hard'){
        time = 3000;
    }

    base = time; 

    yourscore.textContent = '';
    screenDisplay('play'); 

    showtimer.textContent = ''; 

    setTimeout(startGame, 1000); 
}

//Start game 
function startGame(){
    if (!activegame){
        return; 
    }

    resetMug();
    clearInterval(counttimer); 
    
    randIndex = Math.floor(Math.random()*mugs.length);
    const mug = mugs[randIndex]; 
    const img = mug.querySelector('.mug-img'); 
    img.classList.add('blink'); 

    setTimeout(() => {
        img.classList.remove('blink');
    }, 300); 

    let timeleft = time; 

    showtimer.textContent = (timeleft/1000).toFixed(1); 
    
    counttimer = setInterval(() => {
        timeleft -= 100;
        if (timeleft <= 0){
            clearInterval(counttimer);
            showtimer.textContent = '0.0'; 
        }
        else {
            showtimer.textContent = (timeleft/1000).toFixed(1); 
        }
    }, 100); 

    timer = setTimeout(() => {
        endGame();
    },time)
}

//Click on mug 
function clickMug(e){
    if (!activegame){
        if (randIndex === null){
            return; 
        }
    }

    const chosenmug = e.currentTarget; 
    const chosennum = chosenmug.index; 

    if (chosennum === randIndex){
        clearTimeout(timer);
        clearInterval(counttimer); 
        score++;
    

        time = time * .8;
        if (time < 500){
            time = 500; 
        }

        setTimeout(startGame, 700); 
    }
    else{
        endGame(); 
    }
}

//End game 
function endGame(){
    activegame = false; 
    clearTimeout(timer); 
    clearInterval(counttimer);
    showtimer.textContent = ''; 
    yourscore.textContent = 'Your score: ' + score; 

    let highscore = false;
    if (score > 0){
        if (topthreescore.length < 3){
            highscore = true; 
        }
        else{
            if (score > topthreescore[topthreescore.length-1].score){
                highscore = true; 
            }
        }
    }

    if (highscore){
        boardform.classList.remove('hidden'); 
    }
    else{
        boardform.classList.add('hidden'); 
    }

    leaderBoard(); 
    screenDisplay('over'); 
}

//Buttons 
playbtn.addEventListener('click', () => {
    gameGrid();
    playGame(); 
})

restartbtn.addEventListener('click', () => {
    gameGrid();
    playGame(); 
})

//Update leaderboard after submit button 
boardform.addEventListener('submit', function(e){
    e.preventDefault();
    
    const name = enteredname.value; 
    topthreescore.push({
        name: name, 
        score: score
    })

    topthreescore.sort(function(i,j){
        return j.score - i.score;
    })

    topthreescore = topthreescore.slice(0,3);

    localStorage.setItem('topthreescore', JSON.stringify(topthreescore));

    leaderBoard();
    boardform.classList.add('hidden');
    enteredname.value = '';

    setTimeout(() => {
        screenDisplay('start');
    }, 7000);
})

screenDisplay('start');
