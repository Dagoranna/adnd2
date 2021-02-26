<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        
        <link rel="stylesheet" href="style.css?33">  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> 
        <script src="utils.js"></script>        

    </head>
    
    <body style="background-color: black;">
        
        <div id="myWrapper">
            <div id="mainHeader" class="f_grav1" >Advanced Dungeons & Dragons</div>
            <div id="dragonBG"></div>
            <div id="root_menu">
            <!--    <div class="root_menu_item f_grav1" onclick="location.href='https://adndworld.000webhostapp.com/objectmap.php';">
                    <p>Игровое поле</p>
                </div>    -->
                <div class="root_menu_item f_grav1" onclick="polydice();">
                    <p>Дайсы</p>
                </div>
                <div class="root_menu_item f_grav1" onclick="location.href='https://www.dandwiki.com/wiki/SRD:System_Reference_Document';">
                    <p>AD&D-справочник</p>
                </div>  
                <div class="root_menu_item f_grav1" onclick="location.href='http://sabbat.su/dd';">
                    <p>Иногда случается...</p>
                </div> 
            </div>
            <!-- <div id="dices" class="dices" style="display: none"> -->
            <div id="dices" class="dices">    
                <div id="dicePanel">
                    <div class="dx" id="d4">
                        <input type="radio" name="dices" value="d4"><font color="#FFCF40">d4</font>
                        <img class="diceImg" id="4" src="/other_img/dice_d4.png">
                    </div>
                    <div class="dx" id="d6">
                        <input type="radio" name="dices" value="d6"><font color="#FFCF40">d6</font>
                        <img class="diceImg" id="6" src="/other_img/dice_d6.png">
                    </div>      
                    <div class="dx" id="d8">
                        <input type="radio" name="dices" value="d8"><font color="#FFCF40">d8</font>
                        <img class="diceImg" id="8" src="/other_img/dice_d8.png">
                    </div>
                    <div class="dx" id="d10">
                        <input type="radio" name="dices" value="d10"><font color="#FFCF40">d10</font>
                        <img class="diceImg" id="10" src="/other_img/dice_d10.png">
                    </div> 
                    <div class="dx" id="d12">
                        <input type="radio" name="dices" value="d12"><font color="#FFCF40">d12</font>
                        <img class="diceImg" id="12" src="/other_img/dice_d12.png">
                    </div>
                    <div class="dx" id="d20">
                        <input type="radio" name="dices" id="mainDice" value="d20"><font color="#FFCF40">d20</font>
                        <img class="diceImg diceActive" id="20" src="/other_img/dice_d20.png">
                    </div>                      
                    <div class="dx" id="d100">
                        <input type="radio" name="dices" value="d100"><font color="#FFCF40">d100</font>
                        <img class="diceImg" id="100" src="/other_img/dice_d100.png">
                    </div>                     
                </div>
                
                <div id="gameData" class="emeraldFrame">
                    <div>
                        <p class="f_grav1"><font size="5" color="#FFCF40">Код игры:</font></p>
                        
                        <input type="text" id="gameId" class="diceInputs">
                        <br>
                        <p class="f_grav1"><font size="5" color="#FFCF40">Имя игрока:</font></p>
                        
                        <input type="text" id="gamerName" class="diceInputs">
                        <div id="gamerColor" onclick="chooseColor();">
                            <select id="colorSelector" name="myColor" style="display: none; color: gold;" onchange="changeColor();" onblur="$(this).hide();">
                                <option style="color: white">White</option>
                                <option style="color: Silver">Silver</option>
                                <option selected style="color: Gold">Gold</option>
                                <option style="color: Fuchsia">Fuchsia</option>
                                <option style="color: Purple">Purple</option>
                                <option style="color: Red">Red</option>
                                <option style="color: Maroon">Maroon</option>
                                <option style="color: Yellow">Yellow</option>
                                <option style="color: Olive">Olive</option>
                                <option style="color: Lime">Lime</option>
                                <option style="color: Green">Green</option>
                                <option style="color: Aqua">Aqua</option>
                                <option style="color: Teal">Teal</option>
                                <option style="color: Blue">Blue</option>
                                <option style="color: Navy">Navy</option> 
                            </select>
                        </div>    
                        <br>
                    </div>
                </div>
                <div id="gameLog" class="emeraldFrame"></div>
                <div id="myRoll">
                    <div id="diceRoll" class="f_grav1 emeraldFrame" onclick="sendMessage();">
                        <span class="centerText unselectable">Бросок</span>
                    </div>
                    <p class="f_grav1" id="pNumber"><font size="4" color="#FFCF40">Количество бросков:</font></p>
                    <input name="diceNumber" id="diceNumber" type="number" value="1" max="100" min="1">  
                </div>    
            </div>
        </div>
        
        <script>
            var socket;
            let gameLog = document.getElementById("gameLog");
            let myInput;
            let currGamerName;
            let currGameId; 
            
            
            
            function polydice(){
               $("#dices").toggle();
            }
            
            function answerDecoding(answer){
                //dice|gameId|gamerName|20|1|16
                //0    1      2         3  4 5
                //messageArr[4] - количество бросков
                let decAnswer;
                let messageArr = answer.split('|'); 
                let numRolls = Number(messageArr[4]);
                let diceSum = 0;
                
                if (messageArr[2].indexOf('~') > 0){
                    let myGamerName = messageArr[2].slice(0,messageArr[2].indexOf('~'));
                    let myGamerColor = messageArr[2].slice(messageArr[2].indexOf('~')+1);
                    decAnswer = '<p><b><span style="color:' +myGamerColor+ '">' + myGamerName + ' : </span></b>';
                    console.log(decAnswer);
                }else{
                    decAnswer = '<p><b>' + messageArr[2] + ': </b>';
                }
               
                if (numRolls ==1){
                    decAnswer += messageArr[5]
                }else{
                    for (let i = 1; i < numRolls; i++){
                        decAnswer += messageArr[i+4] + ', ';
                        diceSum += Number(messageArr[i+4]);
                    }
                    diceSum += Number(messageArr[4 + numRolls]);
                    decAnswer += messageArr[4 + numRolls] +' (в сумме ' +diceSum+ ')';
                    
                }
                decAnswer += ' на d' + messageArr[3] + '</p>';
                return decAnswer;
            }
            
            //$("#dices").draggable();
            $("#mainDice").prop('checked', true);
 
            $('#dicePanel').on('click', '.dx', function(event){
                
                let target = event.target.closest(".dx");
                let diceButton = target.firstElementChild;
                let diceImg = target.lastElementChild;
                //  $(rbuttn).hide(); //FOR TEST, работает!
                
                $(diceButton).prop('checked', true);
                //diceActive
                $(".diceActive").removeClass("diceActive");
                
                $(diceImg).addClass("diceActive");
                
            });
            
            function chooseColor(){
                let colorBox = document.getElementById("colorSelector");
                $(colorBox).show();
            }
            
            function changeColor(){
                let colorBox = document.getElementById("colorSelector");
                let choosedColor = colorBox.value;
                colorBox.style.color = choosedColor;
                document.getElementById("gamerName").style.color = choosedColor;
                //$(colorBox).hide();
            }
            
            
            
            connect();
            
            function connect(){
                socket = new WebSocket("wss://radiant-peak-08482.herokuapp.com");
                console.log('открываем соединение...');
            }
            
            function reconnect(){
                socket = new WebSocket("wss://radiant-peak-08482.herokuapp.com");
                socket.addEventListener('open', function (event) {
                    console.log('соединение восстановлено');
                });
                socket.addEventListener('message', function (event) {
                    console.log(event.data);
                    if (event.data.indexOf('dice') == 0){
                        //dice|111|eeee|20|1|16
                        //let messageArr = event.data.split('|'); 
                        //gameLog.innerHTML = '<p><b>' + messageArr[2] + ': </b>' + messageArr[5] + ' на d' + messageArr[3] + '</p>' + gameLog.innerHTML;
                        gameLog.innerHTML = answerDecoding(event.data) + gameLog.innerHTML;
                    }                    
                });
                socket.addEventListener('close', function (event) {
                    //gameLog.innerHTML = '<p>соединение закрыто ' + event.code+ '</p>' +gameLog.innerHTML;
                    if (!(event.code == 1005)){
                        reconnect();
                    }
                });                  
            }            
            
            function sendMessage(){
                currGamerName = document.getElementById("gamerName").value;
                if (currGamerName == "") {
                    currGamerName = "аноним";
                }
                
                currGamerName += '~' + document.getElementById("gamerName").style.color;
                
                currGameId = document.getElementById("gameId").value;
                if (currGameId == "") {
                    currGameId = "zerogame";
                }                
                
                //myInput = 'dice|' + currGameId + '|' + currGamerName + '|' + $(".diceActive")[0].id + '|1' ;
                myInput = 'dice|' + currGameId + '|' + currGamerName + '|' + $(".diceActive")[0].id + '|' 
                + document.querySelector("input[name=diceNumber]").value;
                
                console.log(myInput);
                
                if (socket.readyState == '3'){
                   reconnect();
                   setTimeout(socket.send, 2000, myInput); 
                }
                socket.send(myInput);
            }
            
            function closeConnection(){
                socket.close();
                console.log('соединение закрыто');
            }
            
            socket.onopen = function(event) {
                
                console.log('соединение установлено');
            };
            
            socket.onmessage = function(event) {
                
                console.log(event.data);
                if (event.data.indexOf('dice') == 0){
                    //dice|gameId|gamerName|20|1|16
                    //let messageArr = event.data.split('|'); 
                    gameLog.innerHTML = answerDecoding(event.data) + gameLog.innerHTML;
                }
                
            };
            
            socket.onclose = function(event) {
                console.log('соединение закрыто ' + event.code);
                if (!(event.code == 1005)){
                    reconnect();
                }
            };
            
            socket.onerror = function(error) {
                console.log('ошибка!');
            };  
            
               
        </script> 

    </body>
</html>