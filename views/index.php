<form>
	<select name="table" onchange="submit()">
    <?php
	foreach($tables as $ptable){ ?>
		<option value='<?=$ptable['Tables_in_'.db]?>' <?= $ptable['Tables_in_'.db] === $currenttable ? ' selected':'' ?>> <?=$ptable['Tables_in_'.db]?> </option>
	<?php } ?>
	</select>
</form>
<br>
<form action="?table=<?= $currenttable?>">
    <input type='text' name='query' value="<?= isset($_GET["query"]) ? $_GET["query"] : "" ?>"><br>
	<input type='hidden' name='table' value='<?= $currenttable ?>'>
	<input type='hidden' name='page' value=0>
	<input type='submit'>
</form>
<br>
<?php for ($i = 0 ; $i < count($pageSymbols) ; $i++) { ?>
    <a href='<?= $genericPageURL.$pageNumbers[$i] ?>'> <?= $pageSymbols[$i] ?> </a><span>&nbsp;</span>
<?php } ?>
<br>
<table border=1>
	<tr id="tablenames">
	<?php foreach ( $colarr as $row ){ ?>
		<th><?= $row['Field'] ?></th>
	<?php } ?> 
	</tr> 
	<?php foreach ($tableRows as $row){ ?>
		<tr id="r<?=reset($row)?>">
		<?php foreach ($colarr as $i){ ?>
			<td data-type="<?=explode('(',$i['Type'])[0]?>"><?= $row[$i['Field']] ?></td>
		<?php } 
        if ($tienePermisosDeEditar){ 
            ?>
                <td class='edit'>
                    <button onclick="editrow(<?= reset($row) ?>)">✏️</button>
                </td>
                <td class='delete'>
                    <button onclick="delrow(<?= reset($row) ?>)">❌</button>
                </td>
            </tr>
            <?php
        } ?>



        
		</tr>
	<?php } ?>
</table>


<?php if ($addlink){ ?>
    <a style="padding:5px; border-width:1px; border-radius=2px; border-color='#444'" href="add.php?table=<?=$currenttable?>">Añadir</a>
<?php } ?>



<script>
function getInput(key){
    switch (key) {
        case "int":
        case "varchar":
        case "datetime":
        case "date":
            return `<input type="${types[key]}">`;
        case "tinyint":
            return `<input type="number" max=0 min=1 >`;
        case "text":
            return `<textarea>`;
        case "char":
            return `<input type="text" maxlenth=1>`;
        default:
            break;
    }
}
var types= {
    "int":"number",
    "varchar":"text",
    "datetime":"datetime",
    "tinyint":"checkbox",
    "date":"date",
    "text":"textarea",
    "char":"text"
}

var olddata={};
var fieldnames=Array.prototype.map.call(document.getElementById("tablenames").children, x=>x.innerText);
document.getElementById("tablenames").children
function editrow(id){
    let row=document.querySelector(`#r${id}`);

    olddata[id]={};
    
    

    fieldnames.forEach(x=>{
        let field = row.children[fieldnames.indexOf(x)]
        olddata[id][x] = field.innerHTML;
        field.innerHTML=getInput(field.getAttribute("data-type"));
        field.childNodes[0].value=olddata[id][x];
        field.childNodes[0].name=x;
        
        
    });
    row.querySelector('.edit').innerHTML=`<button onclick='moduser(${id})'>✔</button><button  onclick='cancelmod(${id})'>✖</button>`;
    
}

function cancelmod(id){
    let row=document.querySelector(`#r${id}`);
    fieldnames.forEach(x=>{
        row.children[fieldnames.indexOf(x)].innerHTML=olddata[id][x];
    });
    row.querySelector('.edit').innerHTML=`<button onclick=\"editrow(${id})\">✏️</button>`;
}

function moduser(id){
    var row=document.querySelector(`#r${id}`);
    var formData= new FormData();
    formData.set("id", id);
    formData.set("table", document.querySelector("select").value);
        
    row.querySelectorAll("input,textarea").forEach(element => {
        formData.set(element.name,element.value);
    });

    fetch("/api.php", { method:"POST", body:formData, credentials: 'same-origin', mode: 'same-origin', cache: 'no-cache', })
    .then(CheckError)
    .then((jsonResponse) => {
        alert("ok");
        window.location.reload(true);  
    }).catch((error) => {
        console.log(error);
        if (confirm("falló. reintentar?")) {
            delrow(id);
        }   
        
    });
}

function CheckError(response) {
    if (response.status >= 200 && response.status <= 299) {
        return response.json();
    } else {
        throw Error(response.statusText);
    }
}

function delrow(id){
    
    var formData = new FormData();
    formData.set("delete", 1);
    formData.set("id", id);
    formData.set("table", document.querySelector("select").value);
    fetch("/api.php", { method:"POST", body:formData, credentials: 'same-origin', mode: 'same-origin', cache: 'no-cache', })
    .then(CheckError)
    .then((jsonResponse) => {
        alert("ok");
        window.location.reload(true);  
    }).catch((error) => {
        console.log(error);
        if (confirm("falló. reintentar?")) {
            delrow(id);
        }   
        
    });
}

</script>
