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