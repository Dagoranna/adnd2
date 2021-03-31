<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css?34">  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> 
  
        <!-- версия для разработки, отображает полезные предупреждения в консоли -->
        <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
  
        <!-- production-версия, оптимизированная для размера и скорости
        <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>  -->          

    </head>
    
    <body style="background-color: black;">
        <div id="myWrapper">
            <div id="flexWrapper">
                <div id="dices"> 
                    <div id="dicePanel">
                        <div class="dx" id="d4">
                            <div><input type="radio" name="dices" value="d4"></div>    
                            <div>d4</div>
                            <div class="nowrapHelper"></div>
                            <div><img class="diceImg" id="4" src="/other_img/dice_d4.png"></div>    
                        </div>
                        <div class="dx" id="d6">
                            <div><input type="radio" name="dices" value="d6"></div>    
                            <div>d6</div>
                            <div class="nowrapHelper"></div>
                            <div><img class="diceImg" id="6" src="/other_img/dice_d6.png"></div>    
                        </div>     
                        <div class="dx" id="d8">
                            <div><input type="radio" name="dices" value="d8"></div>    
                            <div>d8</div>
                            <div class="nowrapHelper"></div>
                            <div><img class="diceImg" id="8" src="/other_img/dice_d8.png"></div>    
                        </div>
                        <div class="dx" id="d10">
                            <div><input type="radio" name="dices" value="d10"></div>    
                            <div>d10</div>
                            <div class="nowrapHelper"></div>
                            <div><img class="diceImg" id="10" src="/other_img/dice_d10.png"></div>    
                        </div>
                        <div class="dx" id="d12">
                            <div><input type="radio" name="dices" value="d12"></div>    
                            <div>d12</div>
                            <div class="nowrapHelper"></div>
                            <div><img class="diceImg" id="12" src="/other_img/dice_d12.png"></div>    
                        </div>
                        <div class="dx" id="d20">
                            <div><input type="radio" name="dices" value="d20" id="mainDice"></div>    
                            <div>d20</div>
                            <div class="nowrapHelper"></div>
                            <div><img class="diceImg diceActive" id="20" src="/other_img/dice_d20.png"></div>
                        </div> 
                        <div class="dx" id="d100">
                            <div><input type="radio" name="dices" value="d100"></div>    
                            <div>d100</div>
                            <div class="nowrapHelper"></div>
                            <div><img class="diceImg" id="100" src="/other_img/dice_d100.png"></div>    
                        </div>                                         
                    </div>
                    <div id="diceField">
                        <div id="gameData" class="silverFrame">
                            <div class="f_grav1 unselectable">Код игры:</div>
                            <input type="text" id="gameId" class="diceInputs" onchange="sendServiceMessage();">
                            <br>
                            <div class="f_grav1 unselectable">Имя игрока:</div>
                            <input type="text" id="gamerName" class="diceInputs">
                            <div id="gamerColor" onclick="chooseColor();">
                                <select id="colorSelector" name="myColor" style="display: none; color: silver;" onchange="changeColor();" onblur="$(this).hide();">
                                    <option style="color: white">White</option>
                                    <option selected style="color: Silver">Silver</option>
                                    <option style="color: Gold">Gold</option>
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
                        </div>
                        <div id="gameLog" class="silverFrame"></div>
                        <div id="flexHelper1" class="nowrapHelper"></div> 
                        <div id="diceRollProps">
                            <div id="diceRoll" onclick="sendDiceMessage();">
                                <span class="centerText unselectable">Бросок</span>
                            </div>
                            <div id="pNumber">
                                <div id="pNumberText" class="unselectable">Количество бросков:</div>
                                <div>
                                    <input name="diceNumber" id="diceNumber" type="number" value="1" max="100" min="1">  
                                </div>
                            </div>
                            <div id="charsheetButton" onclick="charsheetToggle();">
                                <span class="centerText unselectable">Чаршит</span>
                            </div>
                        </div> 
                    </div>
                </div>
         <!--       <div id="charsheet" style="display: none;">  -->
                <div id="charsheet">
                    <div id="sheetTopMenu">
                        <div id="saveButton" class="smallButton unselectable">Сохранить</div>
                        <div id="loadButton" class="smallButton unselectable">Загрузить</div>
                        <div class="nowrapHelper"></div>
                    </div>
                    <div id="skills">
                    <!--   <div class="sheetTitle">Умения</div> -->
                        <skill v-for="(value,index) in myskills" v-bind:name="value" v-bind:skillid="index"></skill>
                        
                    </div>
                </div>                
            </div>
        </div>
        <script>
            var socket;
            let gameLog = document.getElementById("gameLog");
            let myInput;
            let currGamerName;
            let currGameId;
            let charsheet; 
            let skills = {};

            $.getJSON('charsheet.json', function(data) {
                let json = JSON.stringify(data);
                charsheet = JSON.parse(json);
                skills = charsheet.skills; //список названий скилов

                Vue.component('skill',{
                    props: ['name', 'skillid'],
                    template: '<div  v-bind:id="skillid" class="skillcontainer"><div class="skillButton unselectable" onclick="sendSkillMessage(event);">{{ name }}</div><input class="skillinput" type="number" value="0" max="100" min="0"></div>'
                });

                let vueSkills = new Vue({
                    el: '#skills',
                    data: {
                        myskills: skills
                    }
                });                
            });  
            
            function charsheetToggle(){
                $("#charsheet").toggle();
            }

          
            
            function answerDecoding(answer){
                //dice|gameId|gamerName|20|1|16
                //0    1      2         3  4 5
                //messageArr[4] - количество бросков
                let decAnswer;
                let messageArr = answer.split('|'); 
                let numRolls = Number(messageArr[4]);
                let diceSum = 0;
                if (messageArr[0] == 'skill'){
                    console.log('бросок скила');
                    let myGamerName;
                    let myGamerColor='silver';                    
                    //бросок скила; вид skill|23452|Icy|Decipher Script|22|5 (22 на скиле, 5 на кубе)
                    //                    0     1    2       3           4 5  
                    if (messageArr[2].indexOf('~') > 0){
                        myGamerName = messageArr[2].slice(0,messageArr[2].indexOf('~'));
                        myGamerColor = messageArr[2].slice(messageArr[2].indexOf('~')+1);
                        decAnswer = '<p><b><span style="color:' +myGamerColor+ '">' + myGamerName + ' : </span></b>';
                        console.log(decAnswer);
                    }else{
                        decAnswer = '<p><b>' + messageArr[2] + ': </b>';
                    }
                    decAnswer += Number(messageArr[4]) + Number(messageArr[5]) + ' на <span style="color:' +myGamerColor+ '">' + messageArr[3] + '</span> (' + messageArr[5] + ' на d20 + '+messageArr[4]+')</p>';
                } else if (messageArr[3] == 13){
                    //это было служебное сообщение для обновления данных о клиенте на сервере
                    //выводить ничего не надо
                    decAnswer = "";
                } else {
                    if (messageArr[2].indexOf('~') > 0){
                        let myGamerName = messageArr[2].slice(0,messageArr[2].indexOf('~'));
                        let myGamerColor = messageArr[2].slice(messageArr[2].indexOf('~')+1);
                        decAnswer = '<p><b><span style="color:' +myGamerColor+ '">' + myGamerName + ' : </span></b>';
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
                }
                console.log(decAnswer);
                return decAnswer;
            }
            
            //$("#dices").draggable();
            
            $("#mainDice").prop('checked', true);
            
            $('#dicePanel').on('click', '.dx', function(event){

                let target = event.target.closest(".dx");
                let diceButton = target.getElementsByTagName('input')[0];
                let diceImg = target.getElementsByTagName('img')[0];
                
                $(diceButton).prop('checked', true);
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
            }

            connect();
            
            function connect(){
                socket = new WebSocket("wss://radiant-peak-08482.herokuapp.com");
                console.log('открываем соединение...');
                setTimeout(sendServiceMessage, 2000); 
                
            }
            
            function reconnect(){
                socket = new WebSocket("wss://radiant-peak-08482.herokuapp.com");
                socket.addEventListener('open', function (event) {
                    sendServiceMessage();
                    console.log('соединение восстановлено');
                });
                socket.addEventListener('message', function (event) {
                    console.log(event.data);
                    if ((event.data.indexOf('dice') == 0)||(event.data.indexOf('skill') == 0)){
                        //dice|111|eeee|20|1|16
                        gameLog.innerHTML = answerDecoding(event.data) + gameLog.innerHTML;
                    }                    
                });
                socket.addEventListener('close', function (event) {
                    if (!(event.code == 1005)){
                        reconnect();
                    }
                });                  
            }            
            
            function sendDiceMessage(){
                currGamerName = document.getElementById("gamerName").value;
                if (currGamerName == "") {
                    currGamerName = "аноним";
                }
                
                currGamerName += '~' + document.getElementById("gamerName").style.color;
                
                currGameId = document.getElementById("gameId").value;
                if (currGameId == "") {
                    currGameId = "zerogame";
                }                
                
                myInput = 'dice|' + currGameId + '|' + currGamerName + '|' + $(".diceActive")[0].id + '|' 
                + document.querySelector("input[name=diceNumber]").value;
                
                console.log(myInput);
                
                if (socket.readyState == '3'){
                   reconnect();
                   setTimeout(socket.send, 2000, myInput); 
                }
                socket.send(myInput);
            }
            
            function sendServiceMessage(){
                currGamerName = document.getElementById("gamerName").value;
                if (currGamerName == "") {
                    currGamerName = "аноним";
                }
                
                currGamerName += '~' + document.getElementById("gamerName").style.color;
                
                currGameId = document.getElementById("gameId").value;
                if (currGameId == "") {
                    currGameId = "zerogame";
                }                
                
                myInput = 'dice|' + currGameId + '|' + currGamerName + '|13|1';
                
                console.log(myInput);
                
                if (socket.readyState == '3'){
                   reconnect();
                   setTimeout(socket.send, 2000, myInput); 
                }
                socket.send(myInput);
            }   

            function sendSkillMessage(event){
                currGamerName = document.getElementById("gamerName").value;
                if (currGamerName == "") {
                    currGamerName = "аноним";
                }
                
                currGamerName += '~' + document.getElementById("gamerName").style.color;
                
                currGameId = document.getElementById("gameId").value;
                if (currGameId == "") {
                    currGameId = "zerogame";
                }                
                //например: skill|23452|Icy|Decipher Script|18

                let parentSkillName = event.target.parentElement.firstChild.innerHTML;
                let parentSkillValue = event.target.parentElement.lastChild.value;
                /*<div id="disable-device" class="skillcontainer">
                    <div class="skillButton unselectable"> Disable Device</div>
                    <input type="number" value="0" max="100" min="0" class="skillinput">
                </div>*/

                myInput = 'skill|' + currGameId + '|' + currGamerName + '|' + parentSkillName + '|' + parentSkillValue;

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
                sendServiceMessage();
                console.log('соединение установлено');
            };
            
            socket.onmessage = function(event) {
                
                console.log(event.data);
                if ((event.data.indexOf('dice') == 0)||(event.data.indexOf('skill') == 0)){
                    //dice|gameId|gamerName|20|1|16
                    //let messageArr = event.data.split('|'); 
                    console.log('разбираем сообщение');
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