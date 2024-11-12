<form action="/api/process" enctype="multipart/form-data" method="post" name="editProjectForm">
   omposer dump-autoload
   <div id="hidden1">
      <p align="center">Основные свойства</p>
      <table width="500px" align="center">
         <tbody>
            <tr>
               <td align="right"><label for="">Название проекта:</label></td>
               <td align="left"><input name="title" class="modal-task" style="width: 100%;" type="text" value="Б-ОЦБ-"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Категория:</label></td>
               <td align="left">
                  <select name="category" class="modal-test" style="width: 100%;">
                     <option value="df_cat_0" disabled="disabled">Категория не выбрана</option>
                     <option value="df_cat_1">Дома из профилированного бруса</option>
                     <option value="df_cat_2">Бани из клееного бруса</option>
                     <option value="df_cat_3">Дома из блоков</option>
                     <option value="df_cat_4">Дома из оцилиндрованного бревна</option>
                     <option value="df_cat_5">Бани из бруса камерной сушки</option>
                     <option value="df_cat_6">Бани из бруса сосна/ель</option>
                     <option value="df_cat_7">Бани из оцилиндрованного бревна</option>
                     <option value="df_cat_8">Дом-баня из бруса</option>
                     <option value="df_cat_9">Дома из бруса камерной сушки</option>
                     <option value="df_cat_10">Дома из клееного бруса</option>
                     <option value="df_cat_11">Бани с бассейном</option>
                     <option value="df_cat_12">Каркасные дома</option>
                     <option value="df_cat_13">Бани из бревна кедра</option>
                     <option value="df_cat_14">Бани из бревна лиственницы</option>
                     <option value="df_cat_15">Бани из бруса кедра</option>
                     <option value="df_cat_16">Бани из бруса лиственницы</option>
                     <option value="df_cat_17">Дачные дома</option>
                     <option value="df_cat_18">Дом-баня из бревна</option>
                     <option value="df_cat_19">Дома из бревна кедра</option>
                     <option value="df_cat_20">Дома из бревна лиственницы</option>
                     <option value="df_cat_21">Дома из бруса кедра</option>
                     <option value="df_cat_22">Дома из бруса лиственницы</option>
                  </select>
               </td>
            </tr>
            <tr>
               <td align="right"><label for="">Общая площадь:</label></td>
               <td align="left"><input name="size" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Длина:</label></td>
               <td align="left"><input name="length" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Ширина:</label>
               </td>
               <td align="left"><input name="width" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">ID проекта:</label></td>
               <td align="left">
                  <input name="code" class="modal-task" style="width: 100%;" type="text">
               </td>
            </tr>
            <tr>
               <td align="right">
                  <label for="">Количество заказов:</label>
               </td>
               <td align="left">
                  <input name="numOrders" class="modal-task" style="width: 100%;" type="text">
               </td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">Популярный проект:</label></td>
               <td align="left">
                  <select name="popular" class="modal-test" style="width: 100%;">
                     <option value="0" selected="true">Нет</option>
                     <option value="1">Да</option>
                  </select>
               </td>
            </tr>
            <tr style="display: none;">
               <td align="right">
                  <label for="">Префикс цены:</label>
               </td>
               <td align="left">
                  <select name="prefix" class="modal-test" style="width: 100%;">
                     <option value="0" selected="true">не установлено</option>
                     <option value="1">от</option>
                  </select>
               </td>
            </tr>
            <tr style="display: none;">
               <td align="right">
                  <label for="">Базовая цена:</label>
               </td>
               <td align="left">
                  <input name="price" class="modal-task" style="width: 100%;" type="text">
               </td>
            </tr>
            <tr>
               <td align="right"><label for="">Тип материала:</label></td>
               <td align="left">
                  <select name="materialType" class="modal-test" style="width: 100%;">
                     <option value="df_mat_0">Не установлено</option>
                     <option value="df_mat_1" selected="true">Бревно</option>
                     <option value="df_mat_2">Брус</option>
                     <option value="df_mat_3">Блочный</option>
                     <option value="df_mat_4">Каркасный</option>
                     <option value="df_mat_5">Фарферк</option>
                  </select>
               </td>
            </tr>
            <tr>
               <td align="right"><label for="">Этажность:</label></td>
               <td align="left">
                  <select name="floors" class="modal-test" style="width: 100%;">
                     <option value="1" selected="true">1 этаж</option>
                     <option value="2">2 этажа</option>
                     <option value="3">2 этажа (мансарда)</option>
                     <option value="4">2 этажа + мансарда</option>
                     <option value="5">3 этажа</option>
                  </select>
               </td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">Фундамент (тип):</label></td>
               <td align="left">
                  <select name="baseType" class="modal-test" style="width: 100%;">
                     <option value="0" selected="true">нет</option>
                     <option value="1">Баня</option>
                     <option value="2">Дом</option>
                  </select>
               </td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">Тип крыши:</label></td>
               <td align="left">
                  <select name="roofType" class="modal-test" style="width: 100%;">
                     <option value="0" selected="true">нет</option>
                     <option value="1">1</option>
                     <option value="2">2</option>
                     <option value="3">3</option>
                  </select>
               </td>
            </tr>
            <tr>
               <td align="right"><label for="">S крыши:</label></td>
               <td align="left"><input name="roofSquare" class="modal-task" style="width: 100%;" type="text" ></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">S дома:</label></td>
               <td align="left"><input name="mainSquare" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Фундамент м.пог:</label></td>
               <td align="left">
                  <input name="baseLength" class="modal-task" style="width: 100%;" type="text">
               </td>
            </tr>
            <tr>
               <td align="right"><label for="">База ОЦБ 200 раб:</label></td>
               <td align="left"><input name="baseD20" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">База ОЦБ 200 с отходом:</label></td>
               <td align="left"><input name="baseD20F" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">База рубленное 200 раб:</label></td>
               <td align="left"><input name="baseD20Rub" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">База рубленное 200 с отходом:</label></td>
               <td align="left"><input name="baseD20RubF" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">База брус 145x140 раб:</label></td>
               <td align="left"><input name="baseBalk1" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">База брус 145x140 с отходом:</label></td>
               <td align="left"><input name="baseBalkF" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">База брус 145x190:</label></td>
               <td align="left"><input name="baseBalk2" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <table id="baseType" style="display: none;">
         <tbody>
            <tr>
               <td align="center">
                  <select id="typeBase" class="modal-test" style="width: 100%;">
                     <option value="df_room_0" selected="true" disabled="disabled">Тип не выбран</option>
                     <option value="df_room_1">Крыльцо</option>
                     <option value="df_room_2">Терраса</option>
                     <option value="df_room_3">Закрытая терраса</option>
                     <option value="df_room_4">Навес</option>
                     <option value="df_room_5">Гараж</option>
                     <option value="df_room_6">Тамбур</option>
                     <option value="df_room_7">Холл</option>
                     <option value="df_room_8">Прихожая</option>
                     <option value="df_room_9">Прачечная</option>
                     <option value="df_room_10">С/У</option>
                     <option value="df_room_11">Кухня</option>
                     <option value="df_room_12">Кухня-гостиная</option>
                     <option value="df_room_13">Гостиная</option>
                     <option value="df_room_14">Котельная</option>
                     <option value="df_room_15">Гардероб</option>
                     <option value="df_room_16">Кабинет</option>
                     <option value="df_room_17">Спальня</option>
                     <option value="df_room_18">Комната</option>
                     <option value="df_room_19">Гараж</option>
                     <option value="df_room_20">Кладовая</option>
                     <option value="df_room_21">Парная</option>
                     <option value="df_room_22">Помывочная</option>
                     <option value="df_room_23">Комната отдыха</option>
                     <option value="df_room_24">Балкон</option>
                     <option value="df_room_25">Антресоль</option>
                     <option value="df_room_26">Второй свет</option>
                     <option value="df_room_27">Помещение</option>
                     <option value="df_room_28">Чердачное помещение</option>
                     <option value="df_room_29">Хозблок</option>
                     <option value="df_room_32">Коридор</option>
                     <option value="df_room_33">Кухня-столовая</option>
                     <option value="df_room_34">Столовая</option>
                  </select>
               </td>
               <td align="center">
                  <input id="squareBase" class="modal-task" style="width: 100%;" type="text">
                  <div style="display: none;" id="locationBase">|</div>
               </td>
               <td align="center"><input id="widthBase" class="modal-task" style="width: 100%;" type="text"></td>
               <td align="center"><input id="lengthBase" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <br><br>
      <p align="center">Помещения цокольный этаж</p>
      <table width="50%" id="inputTableFloor0" align="center">
         <tbody id="pomech0">
            <tr>
               <td align="center"><label for="">Тип</label></td>
               <td align="center"><label for="">Номер комнаты</label></td>
               <td align="center"><label for="">Длина</label></td>
               <td align="center"><label for="">Ширина</label></td>
            </tr>
         </tbody>
         <tbody>
            <tr>
               <td colspan="4" align="center">
                  <button type="button" id="addRowButton" onclick="addInputRow('inputTableFloor0')" class="nav-link btn btn-news-noact btn-lg"  style="width: 200px;">Добавить помещение</button>
               </td>
            </tr>
         </tbody>
      </table>
      <br>
      <p align="center">Помещения 1 этаж</p>
      <table width="50%" id="inputTableFloor1" align="center">
         <tbody id="pomech1">
            <tr>
               <td align="center"><label for="">Тип</label></td>
               <td align="center"><label for="">Номер комнаты</label></td>
               <td align="center"><label for="">Длина</label></td>
               <td align="center"><label for="">Ширина</label></td>
            </tr>
         </tbody>
         <tbody>
            <tr>
               <td colspan="4" align="center"><button type="button" class="nav-link btn btn-news-noact btn-lg" onclick="addInputRow('inputTableFloor1')" style="width: 200px;">Добавить помещение</button></td>
            </tr>
         </tbody>
      </table>
      <br>
      <p align="center">Помещения 2 этаж</p>
      <table width="50%" id="inputTableFloor2" align="center">
         <tbody id="pomech2">
            <tr>
               <td align="center"><label for="">Тип</label></td>
               <td align="center"><label for="">Номер комнаты</label></td>
               <td align="center"><label for="">Длина</label></td>
               <td align="center"><label for="">Ширина</label></td>
            </tr>
         </tbody>
         <tbody>
            <tr>
               <td colspan="4" align="center"><button type="button" class="nav-link btn btn-news-noact btn-lg" onclick="addInputRow('inputTableFloor2')" style="width: 200px;">Добавить помещение</button></td>
            </tr>
         </tbody>
      </table>
      <br>
      <p align="center">Помещения 3 этаж</p>
      <table width="50%" id="inputTableFloor3" align="center">
         <tbody id="pomech3">
            <tr>
               <td align="center"><label for="">Тип</label></td>
               <td align="center"><label for="">Номер комнаты</label></td>
               <td align="center"><label for="">Длина</label></td>
               <td align="center"><label for="">Ширина</label></td>
            </tr>
         </tbody>
         <tbody>
            <tr>
               <td colspan="4" align="center"><button type="button" class="nav-link btn btn-news-noact btn-lg" onclick="addInputRow('inputTableFloor3')"  style="width: 200px;">Добавить помещение</button></td>
            </tr>
         </tbody>
      </table>
      <br>
      <p align="center">Помещения чердака</p>
      <table width="50%" id="inputTableFloor4" align="center">
         <tbody id="pomech3">
            <tr>
               <td align="center"><label for="">Тип</label></td>
               <td align="center"><label for="">Номер комнаты</label></td>
               <td align="center"><label for="">Длина</label></td>
               <td align="center"><label for="">Ширина</label></td>
            </tr>
         </tbody>
         <tbody>
            <tr>
               <td colspan="4" align="center"><button type="button" class="nav-link btn btn-news-noact btn-lg" onclick="addInputRow('inputTableFloor4')"  style="width: 200px;">Добавить помещение</button></td>
            </tr>
         </tbody>
      </table>
      <br>
      <div id="room0" style="display: none;">107</div>
      <div id="room1" style="display: none;">108</div>
      <div id="room2" style="display: none;">109</div>
      <div id="room3" style="display: none;">110</div>
      <div id="room4" style="display: none;">111</div>
      <div id="room5" style="display: none;">112</div>
      <div id="room6" style="display: none;">113</div>
      <div id="room7" style="display: none;">114</div>
      <div id="room8" style="display: none;">115</div>
      <div id="roomsCount" style="display: none;">9</div>
      <br>
      <p align="center">Стены и перерубы</p>
      <table width="500px" align="center">
         <tbody>
            <tr style="display: none;">
               <td align="right"><label for="">Цокольное перекрытие м3 50х200:</label></td>
               <td align="left"><input name="floorDown" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">S - 1й этаж м2:</label></td>
               <td align="left"><input name="firstFloorSquare" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">1 междуэтажное перекрытие м3 50х200:</label></td>
               <td align="left"><input name="floorMid" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">S - 2й этаж м2:</label></td>
               <td align="left"><input name="secondFloorSquare" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">2-3 междуэтажное перекрытие м3 50х200:</label></td>
               <td align="left"><input name="floorMid2" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">S - 3й этаж м2:</label></td>
               <td align="left"><input name="thrirdFloorSquare" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">Чердачное перекрытие м3 50х200:</label></td>
               <td align="left"><input name="floorUp" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">S - чердак м2:</label></td>
               <td align="left"><input name="roofFloorSquare" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">ОЦБ Стены м2:</label></td>
               <td align="left"><input name="wallsOut" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">ПБ стены м2:</label></td>
               <td align="left"><input name="wallsIn" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">ПБ Перерубы пог.м:</label></td>
               <td align="left"><input name="wallsPerOut" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">ОЦБ перерубы пог.м:</label></td>
               <td align="left"><input name="wallsPerIn" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Кровля рубероид м2:</label></td>
               <td align="left"><input name="rubRoof" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <br>
      <p align="center">Скаты крыши</p>
      <table id="skbaseType" style="display: none;">
         <tbody>
            <tr>
               <td align="center"><input id="skLengthBase" class="modal-task" style="width: 100%;" type="text"></td>
               <td align="center"><input id="skWidthBase" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <table width="400px" align="center">
         <tbody id="skat">
            <tr>
               <td align="center"><label for="">Ширина, мм</label></td>
               <td align="center"><label for="">Длина, мм</label></td>
            </tr>
            <tr>
               <td colspan="4" align="center"><button type="button" class="nav-link btn btn-news-noact btn-lg" href="#" onclick="addSkat('skat')" style="width: 200px;">Добавить скат</button></td>
            </tr>
         </tbody>
      </table>
      <table id="sbaseType" style="display: none;">
         <tbody>
            <tr>
               <td align="center"><input id="stropBase" class="modal-task" style="width: 100%;" type="text"></td>
               <td align="center"><input id="slengthBase" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <br><br>
      <p align="center">Пирог кровли</p>
      <table width="400px" align="center">
         <tbody>
            <tr>
               <td align="right"><label for="">Объем стропил, м3:</label></td>
               <td align="left"><input name="stropValue" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <br>
      <p align="center">Стропила</p>
      <table width="30%" align="center">
         <tbody id="strop">
            <tr>
               <td align="center"><label for="">Скаты стропила, шт</label></td>
               <td align="center"><label for="">Длина</label></td>
            </tr>
            <tr>
               <td align="center"><input id="strop95" class="modal-task" style="width: 100%;" type="text"></td>
               <td align="center"><input id="slength95" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="center"><input id="strop96" class="modal-task" style="width: 100%;" type="text"></td>
               <td align="center"><input id="slength96" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
         <tbody>
            <tr>
               <td colspan="4" align="center"><button type="button" class="nav-link btn btn-news-noact btn-lg" href="#" onclick="addSkat('strop')" style="width: 200px;">Добавить скат</button></td>
            </tr>
         </tbody>
      </table>
      <table id="ebaseType" style="display: none;">
         <tbody>
            <tr>
               <td align="center"><input id="eskatBase" class="modal-task" style="width: 100%;" type="text"></td>
               <td align="center"><input id="elengthBase" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <br>
      <p align="center">Ендовы</p>
      <table width="30%" align="center">
         <tbody id="endova">
            <tr>
               <td align="center"><label for="">Скаты, шт</label></td>
               <td align="center"><label for="">Длина</label></td>
            </tr>
         </tbody>
         <tbody>
            <tr>
               <td colspan="4" align="center"><button type="button" class="nav-link btn btn-news-noact btn-lg" href="#" onclick="addSkat('endova')" style="width: 200px;">Добавить скат</button></td>
            </tr>
         </tbody>
      </table>
      <div id="cstrop0" style="display: none;">95</div>
      <div id="cstrop1" style="display: none;">96</div>
      <div id="stropCount" style="display: none;">2</div>
      <div id="endovaCount" style="display: none;">0</div>
      <div id="cskat0" style="display: none;">35</div>
      <div id="cskat1" style="display: none;">36</div>
      <div id="cskat2" style="display: none;">37</div>
      <div id="skatCount" style="display: none;">3</div>
      <div id="cml0" style="display: none;">41</div>
      <div id="mlCount" style="display: none;">1</div>
   </div>
   <div id="hidden2" style="display: none;">
      <p align="center">Кровля временная временно не доступна</p>
   </div>
   <div id="hidden3">
      <br>
      <p align="center">Кровля из металлочерепицы</p>
      <table id="mlbaseType" style="display: none;">
         <tbody>
            <tr>
               <td align="center"><input id="mlLengthBase" class="modal-task" style="width: 100%;" type="text"></td>
               <td align="center"><input id="mlCountBase" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <table width="400px" align="center">
         <tbody id="metalList">
            <tr>
               <td align="center"><label for="">Длина листа, мм</label></td>
               <td align="center"><label for="">количество, шт</label></td>
            </tr>
            <tr>
               <td align="center"><input id="mlLength41" class="modal-task" style="width: 100%;" type="text"></td>
               <td align="center"><input id="mlCount41" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
         <tbody>
            <tr>
               <td colspan="4" align="center"><button type="button" class="nav-link btn btn-news-noact btn-lg" href="#" onclick="addSkat('metalList')" style="width: 200px;">Добавить лист</button></td>
            </tr>
         </tbody>
      </table>
      <br>
      <table width="50%" align="center">
         <tbody>
            <tr>
               <td align="right"><label for="">Конек широкий шт:</label></td>
               <td align="left"><input name="mrKon" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Конек односкатной крыши шт:</label></td>
               <td align="left"><input name="mrKonOneSkat" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Планка ветровая шт:</label></td>
               <td align="left"><input name="mrPlanVetr" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Карнизная планка шт:</label></td>
               <td align="left"><input name="mrPlanKar" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Капельники шт:</label></td>
               <td align="left"><input name="mrKapelnik" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Ендова нижняя шт:</label></td>
               <td align="left"><input name="mrEndn" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Ендова верхняя шт:</label></td>
               <td align="left"><input name="mrEndv" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Саморез 35 уп:</label></td>
               <td align="left"><input name="mrSam35" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Саморез 70 уп:</label></td>
               <td align="left"><input name="mrSam70" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Упаковка шт:</label></td>
               <td align="left"><input name="mrPack" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Изоспан АМ 70м2, шт:</label></td>
               <td align="left"><input name="mrIzospanAM" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Изоспан АМ 35м2, шт:</label></td>
               <td align="left"><input name="mrIzospanAM35" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Лента клеещая двухсторонняя шт:</label></td>
               <td align="left"><input name="mrLenta" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Роквул скандик уп:</label></td>
               <td align="left"><input name="mrRokvul" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Изоспан В 70м2, шт:</label></td>
               <td align="left"><input name="mrIzospanB" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Изоспан В 35м2, шт:</label></td>
               <td align="left"><input name="mrIzospanB35" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Примыкание угловое, шт:</label></td>
               <td align="left"><input name="mrPrimUgol" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Примыкание накладное, шт:</label></td>
               <td align="left"><input name="mrPrimNakl" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Утепление кровли 150мм уп:</label></td>
               <td align="left"><input name="mrUtep150" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Утепление кровли 200мм уп:</label></td>
               <td align="left"><input name="mrUtep200" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
   </div>
   <div id="hidden4">
      <br>
      <p align="center">Кровля мягкая</p>
      <table width="50%" align="center">
         <tbody>
            <tr>
               <td align="right"><label for="">Гибкая черепица "Shnglas" коллекция Сальса:</label></td>
               <td align="left"><input name="srCherep" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Подкладочный ковер рул:</label></td>
               <td align="left"><input name="srKover" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Конек карниз шт:</label></td>
               <td align="left"><input name="srKonK" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Мастика 3.6 кг шт:</label></td>
               <td align="left"><input name="srMastika1" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Мастика 12 кг шт:</label></td>
               <td align="left"><input name="srMastika" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Конек широкий шт:</label></td>
               <td align="left"><input name="srKonShir" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Конек односкатной крыши шт:</label></td>
               <td align="left"><input name="srKonOneSkat" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Планка ветровая шт:</label></td>
               <td align="left"><input name="srPlanVetr" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Карнизная планка шт:</label></td>
               <td align="left"><input name="srPlanK" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Капельники шт:</label></td>
               <td align="left"><input name="srKapelnik" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Ендова шт:</label></td>
               <td align="left"><input name="srEndn" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr style="display: none;">
               <td align="right"><label for="">Ендова верхняя шт:</label></td>
               <td align="left"><input name="srEndv" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Гвоздь кровельный уп:</label></td>
               <td align="left"><input name="srGvozd" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Саморез 70 уп:</label></td>
               <td align="left"><input name="srSam70" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Упаковка шт:</label></td>
               <td align="left"><input name="srPack" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Изоспан АМ 70м2, шт:</label></td>
               <td align="left"><input name="srIzospanAM" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Изоспан АМ 35м2, шт:</label></td>
               <td align="left"><input name="srIzospanAM35" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Лента клеещая двухсторонняя шт:</label></td>
               <td align="left"><input name="srLenta" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Роквул скандик уп:</label></td>
               <td align="left"><input name="srRokvul" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Изоспан В 70м2, шт:</label></td>
               <td align="left"><input name="srIzospanB" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Изоспан В 35м2, шт:</label></td>
               <td align="left"><input name="srIzospanB35" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Примыкание угловое, шт:</label></td>
               <td align="left"><input name="srPrimUgol" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Примыкание накладное, шт:</label></td>
               <td align="left"><input name="srPrimNakl" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">OSB-3 9 мм лист:</label></td>
               <td align="left"><input name="srOSB" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Аэратор конька шт:</label></td>
               <td align="left"><input name="srAero" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Аэратор скатный шт:</label></td>
               <td align="left"><input name="srAeroSkat" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Утепление кровли 150мм уп:</label></td>
               <td align="left"><input name="srUtep150" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Утепление кровли 200мм уп:</label></td>
               <td align="left"><input name="srUtep200" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
   </div>
   <div id="hidden5">
      <br>
      <p align="center">Водосточка пластиковая</p>
      <table width="50%" align="center">
         <tbody>
            <tr>
               <td align="right"><label for="">Желоб 3м, шт:</label></td>
               <td align="left"><input name="pvPart1" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Соединитель желоба, шт:</label></td>
               <td align="left"><input name="pvPart2" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Кронштейн желоба, шт:</label></td>
               <td align="left"><input name="pvPart3" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Заглушка, шт:</label></td>
               <td align="left"><input name="pvPart4" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Воронка, шт:</label></td>
               <td align="left"><input name="pvPart5" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Колено, шт:</label></td>
               <td align="left"><input name="pvPart6" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Отвод, шт:</label></td>
               <td align="left"><input name="pvPart7" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Труба 3м, шт:</label></td>
               <td align="left"><input name="pvPart8" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Труба 1м, шт:</label></td>
               <td align="left"><input name="pvPart9" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Хомут трубы, шт:</label></td>
               <td align="left"><input name="pvPart10" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Муфта трубы, шт:</label></td>
               <td align="left"><input name="pvPart11" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Угол желоба 90*, шт:</label></td>
               <td align="left"><input name="pvPart12" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
   </div>
   <div id="hidden6">
      <br>
      <p align="center">Водосточка металлическая</p>
      <table width="50%" align="center">
         <tbody>
            <tr>
               <td align="right"><label for="">Желоб 3м, шт:</label></td>
               <td align="left"><input name="mvPart1" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Соединитель желоба, шт:</label></td>
               <td align="left"><input name="mvPart2" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Кронштейн желоба, шт:</label></td>
               <td align="left"><input name="mvPart3" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Заглушка, шт:</label></td>
               <td align="left"><input name="mvPart4" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Воронка, шт:</label></td>
               <td align="left"><input name="mvPart5" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Колено, шт:</label></td>
               <td align="left"><input name="mvPart6" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Отвод, шт:</label></td>
               <td align="left"><input name="mvPart7" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Труба 3м, шт:</label></td>
               <td align="left"><input name="mvPart8" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Труба 1м, шт:</label></td>
               <td align="left"><input name="mvPart9" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Хомут трубы, шт:</label></td>
               <td align="left"><input name="mvPart10" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Угол желоба 90* внутренний, шт:</label></td>
               <td align="left"><input name="mvPart11" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Угол желоба 90* внешний, шт:</label></td>
               <td align="left"><input name="mvPart12" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
   </div>
   <div id="hidden7">
      <br>
      <p align="center">Фундамент ленточный</p>
      <table width="50%" align="center">
         <tbody>
            <tr>
               <td align="right"><label for="">Длина, пог. м:</label></td>
               <td align="left"><input name="lfLength" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Углы Г-образные, шт:</label></td>
               <td align="left"><input name="lfAngleG" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Углы Т-образные, шт:</label></td>
               <td align="left"><input name="lfAngleT" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Перекрестия +, шт:</label></td>
               <td align="left"><input name="lfAngleX" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">Углы 45 градусов, шт:</label></td>
               <td align="left"><input name="lfAngle45" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <br>
      <p align="center">Фундамент винтовой/жб</p>
      <table width="50%" align="center">
         <tbody>
            <tr>
               <td align="right"><label for="">Длина, пог. м:</label></td>
               <td align="left"><input name="vfLength" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">кол-во свай, шт:</label></td>
               <td align="left"><input name="vfCount" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
            <tr>
               <td align="right"><label for="">объем бруса, м3:</label></td>
               <td align="left"><input name="vfBalk" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <br>
      <p align="center">Фундамент монолитная плита</p>
      <table width="50%" align="center">
         <tbody>
            <tr>
               <td align="right"><label for="">площадь, м2:</label></td>
               <td align="left"><input name="mfSquare" class="modal-task" style="width: 100%;" type="text"></td>
            </tr>
         </tbody>
      </table>
      <div align="center">
         <!-- <form method="post" action="/save_reviews.php"> -->
         <div class="form-row" style="display: block;">
            <label>Изображения:</label>
            <div class="img-list" id="js-file-list"></div>
            <!-- <input id="js-file" type="file" name="file[]" multiple="" accept=".jpg,.jpeg,.png,.gif" onchange="changeImgs();"> -->
            <input type="file" name="images[]" accept=".jpg,.jpeg,.png,.gif" multiple>
         </div>
         <!-- </form> -->
      </div>
   </div>
   <div id="hidden8">
      <p align="center">SEO оптимизация</p>
      <table width="80%" align="center">
         <tbody>
            <tr>
               <td align="right" style="width: 20%;"><label for="">META TITLE:</label></td>
               <td align="left"><input name="MetaTitle" class="modal-task" style="width: 100%; text-align-last: left;" type="text"></td>
            </tr>
            <tr>
               <td align="right" style="width: 20%;"><label for="">META KEYWORDS:</label></td>
               <td align="left"><input name="MetaKeywords" class="modal-task" style="width: 100%; text-align-last: left;" type="text"></td>
            </tr>
            <tr>
               <td align="right" style="width: 20%;"><label for="">META DESCRIPTION:</label></td>
               <td align="left"><textarea name="MetaDesc" class="modal-task" style="width: 100%; height: 100px; text-align-last: left;" type="text"></textarea></td>
            </tr>
            <tr>
               <td align="right" style="width: 20%;"><label for="">Заголовок элемента:</label></td>
               <td align="left"><input name="MetaHeader" class="modal-task" style="width: 100%; text-align-last: left;" type="text"></td>
            </tr>
         </tbody>
      </table>
   </div>
   <div id="hidden8">
      <table width="80%" align="center">
         <input type="submit" size="md" class="flex-shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center font-bold shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-white dark:text-gray-800 inline-flex items-center font-bold px-4 h-9 text-sm flex-shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center font-bold" dusk="create-button" href=""><span class="hidden md:inline-block">Сохранить</span></input>
      </table>
   </div>
</form>