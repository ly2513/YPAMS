var s = [ "s1", "s2", "s3" ];
var opt0 = [ "国家", "省份、州", "地级市、县" ];
function setup() {
    change(0);
}

function loadProvince(regionId) {
    $("#id_provSelect").html("");
    $("#id_provSelect").append("<option value=''>请选择省份</option>");
    var jsonStr = getAddress(regionId, 0);
    for ( var k in jsonStr) {
        $("#id_provSelect").append(
            "<option value='"+k+"'>" + jsonStr[k] + "</option>");
    }
    if (regionId.length != 6) {
        $("#id_citySelect").html("");
        $("#id_citySelect").append("<option value=''>请选择城市</option>");
        $("#id_areaSelect").html("");
        $("#id_areaSelect").append("<option value=''>请选择区域</option>");
    } else {
        $("#id_provSelect").val(regionId.substring(0, 2) + "0000");

    }
}

function loadCity(regionId) {
    $("#id_citySelect").html("");
    $("#id_citySelect").append("<option value=''>请选择城市</option>");
    if (regionId.length != 6) {
        $("#id_areaSelect").html("");
        $("#id_areaSelect").append("<option value=''>请选择区域</option>");
    } else {
        var jsonStr = getAddress(regionId, 1);
        for ( var k in jsonStr) {
            $("#id_citySelect").append(
                "<option value='"+k+"'>" + jsonStr[k] + "</option>");
        }
        if (regionId.substring(2, 6) == "0000") {
            $("#id_areaSelect").html("");
            $("#id_areaSelect").append("<option value=''>请选择区域</option>");
        } else {
            $("#id_citySelect").val(regionId.substring(0, 4) + "00");

        }
    }
}

function loadArea(regionId) {
    $("#id_areaSelect").html("");
    $("#id_areaSelect").append("<option value=''>请选择区域</option>");
    if (regionId.length == 6) {
        var jsonStr = getAddress(regionId, 2);
        for ( var k in jsonStr) {
            $("#id_areaSelect").append(
                "<option value='"+k+"'>" + jsonStr[k] + "</option>");
        }
        if (regionId.substring(4, 6) != "00") {
            $("#id_areaSelect").val(regionId);
        }
    }
}
function getCountry() {
    change(1);
    var country = document.getElementById("s1");
    var province = document.getElementById("s2");
    var city = document.getElementById("s3");
    var provSelect = document.getElementById("id_provSelect");
    var citySelect = document.getElementById("id_citySelect");
    var areaSelect = document.getElementById("id_areaSelect");
    if (country.value == "中国") {
        loadProvince('440116');
        provSelect.style.display = "block";
        province.style.display = "none";
        city.style.display = "none";
    } else {
        province.style.display = "block";
        city.style.display = "block";
        provSelect.style.display = "none";
        citySelect.style.display = "none";
        areaSelect.style.display = "none";
    }
}
function getChinaProv(regionId) {
    document.getElementById("id_citySelect").style.display = "block";
    loadCity(regionId);
}
function getChinaCity(regionId) {
    document.getElementById("id_areaSelect").style.display = "block";
    loadArea(regionId);
}
function getProvince() {
    change(2);
    var province = document.getElementById("s2");
    var city = document.getElementById("s3");
    city.style.display = 'block';
}
function stampUpload() {
    var file = document.getElementById("file");
    var country = $("#s1");
    var state = $("#s2");
    var area = $("#s3");
    var chinaProvince = $("#id_provSelect").find("option:selected");
    var chinaCity = $("#id_citySelect").find("option:selected");
    var chinaArea = $("#id_areaSelect").find("option:selected");
    if (file.value == "") {
        alert("请选择一张邮票！")
    } else if (country.val() == "国家") {
        alert("请选择一个国家！");
    } else if (country.val() == "中国"){
        alert(country.val()+","+chinaProvince.text()+","+chinaCity.text()+","+chinaArea.text());
    }
}