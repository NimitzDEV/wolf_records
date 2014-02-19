function sortCountry(a, b, attr, direction) {
  var rex= RegExp("(.+?)([0-9]+)"),
      aCnt = a.vno.replace(rex,'$1'),
      bCnt = b.vno.replace(rex,'$1');
  if(aCnt === bCnt)
  {
    var aCnt  = parseInt(a.vno.replace(rex,'$2')),
        bCnt  = parseInt(b.vno.replace(rex,'$2'));
  }
  comparison = aCnt > bCnt ? 1:-1;
  return direction > 0 ? comparison : -comparison;
};
function sortDestiny(a, b, attr, direction) {
  var rex= RegExp("([0-9]+)d(.+)"),
      aDay = parseInt(a.des.replace(rex,'$1')),
      bDay = parseInt(b.des.replace(rex,'$1'));
  if(aDay === bDay)
  {
    var aDay  = a.des.replace(rex,'$2'),
        bDay  = b.des.replace(rex,'$2');
  }
  comparison = aDay > bDay ? 1:-1;
  return direction > 0 ? comparison : -comparison;
};
