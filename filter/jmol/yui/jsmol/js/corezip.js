(function(Clazz
,$_A
,$_Ab
,$_AB
,$_AC
,$_AD
,$_AF
,$_AI
,$_AL
,$_AS
,$_B
,$_C
,$_D
,$_E
,$_F
,$_G
,$_H
,$_I
,$_J
,$_K
,$_k
,$_L
,$_M
,$_N
,$_O
,$_P
,$_Q
,$_R
,$_S
,$_s
,$_T
,$_U
,$_V
,$_W
,$_X
,$_Y
,$_Z
,Clazz_doubleToInt
,Clazz_declarePackage
,Clazz_instanceOf
,Clazz_load
,Clazz_instantialize
,Clazz_decorateAsClass
,Clazz_floatToInt
,Clazz_makeConstructor
,Clazz_defineEnumConstant
,Clazz_exceptionOf
,Clazz_newIntArray
,Clazz_defineStatics
,Clazz_newFloatArray
,Clazz_declareType
,Clazz_prepareFields
,Clazz_superConstructor
,Clazz_newByteArray
,Clazz_declareInterface
,Clazz_p0p
,Clazz_pu$h
,Clazz_newShortArray
,Clazz_innerTypeInstance
,Clazz_isClassDefined
,Clazz_prepareCallback
,Clazz_newArray
,Clazz_castNullAs
,Clazz_floatToShort
,Clazz_superCall
,Clazz_decorateAsType
,Clazz_newBooleanArray
,Clazz_newCharArray
,Clazz_implementOf
,Clazz_newDoubleArray
,Clazz_overrideConstructor
,Clazz_supportsNativeObject
,Clazz_extendedObjectMethods
,Clazz_callingStackTraces
,Clazz_clone
,Clazz_doubleToShort
,Clazz_innerFunctions
,Clazz_getInheritedLevel
,Clazz_getParamsType
,Clazz_isAF
,Clazz_isAI
,Clazz_isAS
,Clazz_isASS
,Clazz_isAP
,Clazz_isAFloat
,Clazz_isAII
,Clazz_isAFF
,Clazz_isAFFF
,Clazz_tryToSearchAndExecute
,Clazz_getStackTrace
,Clazz_inheritArgs
){
var $t$;
//var c$;
Clazz_declarePackage ("J.api");
Clazz_declareInterface (J.api, "JmolZipUtility");
Clazz_declarePackage ("J.io2");
Clazz_load (["J.api.JmolZipUtility"], "J.io2.ZipUtil", ["java.io.BufferedInputStream", "$.BufferedReader", "java.lang.Character", "java.util.Hashtable", "$.StringTokenizer", "java.util.zip.CRC32", "$.GZIPInputStream", "$.ZipEntry", "$.ZipInputStream", "javajs.api.ZInputStream", "JU.List", "$.PT", "$.SB", "J.adapter.smarter.AtomSetCollection", "J.api.Interface", "J.io.JmolBinary", "J.io2.JmolZipInputStream", "J.util.Escape", "$.Logger", "$.Txt"], function () {
c$ = Clazz_declareType (J.io2, "ZipUtil", null, J.api.JmolZipUtility);
Clazz_makeConstructor (c$, 
function () {
});
$_V(c$, "newZipInputStream", 
function (is) {
return J.io2.ZipUtil.newZIS (is);
}, "java.io.InputStream");
c$.newZIS = $_M(c$, "newZIS", 
function (is) {
return (Clazz_instanceOf (is, javajs.api.ZInputStream) ? is : Clazz_instanceOf (is, java.io.BufferedInputStream) ?  new J.io2.JmolZipInputStream (is) :  new J.io2.JmolZipInputStream ( new java.io.BufferedInputStream (is)));
}, "java.io.InputStream");
$_V(c$, "getAllZipData", 
function (is, subfileList, name0, binaryFileList, fileData) {
J.io2.ZipUtil.getAllZipDataStatic (is, subfileList, name0, binaryFileList, fileData);
}, "java.io.InputStream,~A,~S,~S,java.util.Map");
c$.getAllZipDataStatic = $_M(c$, "getAllZipDataStatic", 
function (is, subfileList, name0, binaryFileList, fileData) {
var zis = J.io2.ZipUtil.newZIS (is);
var ze;
var listing =  new JU.SB ();
binaryFileList = "|" + binaryFileList + "|";
var prefix = J.util.Txt.join (subfileList, '/', 1);
var prefixd = null;
if (prefix != null) {
prefixd = prefix.substring (0, prefix.indexOf ("/") + 1);
if (prefixd.length == 0) prefixd = null;
}try {
while ((ze = zis.getNextEntry ()) != null) {
var name = ze.getName ();
if (prefix != null && prefixd != null && !(name.equals (prefix) || name.startsWith (prefixd))) continue;
listing.append (name).appendC ('\n');
var sname = "|" + name.substring (name.lastIndexOf ("/") + 1) + "|";
var asBinaryString = (binaryFileList.indexOf (sname) >= 0);
var bytes = J.io.JmolBinary.getStreamBytes (zis, ze.getSize ());
var str;
if (asBinaryString) {
str = J.io2.ZipUtil.getBinaryStringForBytes (bytes);
name += ":asBinaryString";
} else {
str = J.io.JmolBinary.fixUTF (bytes);
}str = "BEGIN Directory Entry " + name + "\n" + str + "\nEND Directory Entry " + name + "\n";
fileData.put (name0 + "|" + name, str);
}
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
} else {
throw e;
}
}
fileData.put ("#Directory_Listing", listing.toString ());
}, "java.io.InputStream,~A,~S,~S,java.util.Map");
c$.getBinaryStringForBytes = $_M(c$, "getBinaryStringForBytes", 
function (bytes) {
var ret =  new JU.SB ();
for (var i = 0; i < bytes.length; i++) ret.append (Integer.toHexString (bytes[i] & 0xFF)).appendC (' ');

return ret.toString ();
}, "~A");
$_V(c$, "getZipFileContents", 
function (bis, list, listPtr, asBufferedInputStream) {
var ret;
if (list == null || listPtr >= list.length) return this.getZipDirectoryAsStringAndClose (bis);
var fileName = list[listPtr];
var zis =  new java.util.zip.ZipInputStream (bis);
var ze;
try {
var isAll = (fileName.equals ("."));
if (isAll || fileName.lastIndexOf ("/") == fileName.length - 1) {
ret =  new JU.SB ();
while ((ze = zis.getNextEntry ()) != null) {
var name = ze.getName ();
if (isAll || name.startsWith (fileName)) ret.append (name).appendC ('\n');
}
var str = ret.toString ();
return (asBufferedInputStream ? J.io.JmolBinary.getBIS (str.getBytes ()) : str);
}var pt = fileName.indexOf (":asBinaryString");
var asBinaryString = (pt > 0);
if (asBinaryString) fileName = fileName.substring (0, pt);
while ((ze = zis.getNextEntry ()) != null && !fileName.equals (ze.getName ())) {
}
var bytes = (ze == null ? null : J.io.JmolBinary.getStreamBytes (zis, ze.getSize ()));
ze = null;
zis.close ();
if (bytes == null) return "";
if (J.io.JmolBinary.isZipB (bytes)) return this.getZipFileContents (J.io.JmolBinary.getBIS (bytes), list, ++listPtr, asBufferedInputStream);
if (asBufferedInputStream) return J.io.JmolBinary.getBIS (bytes);
if (asBinaryString) {
ret =  new JU.SB ();
for (var i = 0; i < bytes.length; i++) ret.append (Integer.toHexString (bytes[i] & 0xFF)).appendC (' ');

return ret.toString ();
}return J.io.JmolBinary.fixUTF (bytes);
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
return "";
} else {
throw e;
}
}
}, "java.io.BufferedInputStream,~A,~N,~B");
$_V(c$, "getZipFileContentsAsBytes", 
function (bis, list, listPtr) {
var ret =  Clazz_newByteArray (0, 0);
var fileName = list[listPtr];
if (fileName.lastIndexOf ("/") == fileName.length - 1) return ret;
try {
bis = J.io.JmolBinary.checkPngZipStream (bis);
var zis =  new java.util.zip.ZipInputStream (bis);
var ze;
while ((ze = zis.getNextEntry ()) != null) {
if (!fileName.equals (ze.getName ())) continue;
var bytes = J.io.JmolBinary.getStreamBytes (zis, ze.getSize ());
return (J.io.JmolBinary.isZipB (bytes) && ++listPtr < list.length ? this.getZipFileContentsAsBytes (J.io.JmolBinary.getBIS (bytes), list, listPtr) : bytes);
}
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
} else {
throw e;
}
}
return ret;
}, "java.io.BufferedInputStream,~A,~N");
$_V(c$, "getZipDirectoryAsStringAndClose", 
function (bis) {
var sb =  new JU.SB ();
var s =  new Array (0);
try {
s = this.getZipDirectoryOrErrorAndClose (bis, false);
bis.close ();
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
J.util.Logger.error (e.toString ());
} else {
throw e;
}
}
for (var i = 0; i < s.length; i++) sb.append (s[i]).appendC ('\n');

return sb.toString ();
}, "java.io.BufferedInputStream");
$_V(c$, "getZipDirectoryAndClose", 
function (bis, addManifest) {
var s =  new Array (0);
try {
s = this.getZipDirectoryOrErrorAndClose (bis, addManifest);
bis.close ();
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
J.util.Logger.error (e.toString ());
} else {
throw e;
}
}
return s;
}, "java.io.BufferedInputStream,~B");
$_M(c$, "getZipDirectoryOrErrorAndClose", 
function (bis, addManifest) {
bis = J.io.JmolBinary.checkPngZipStream (bis);
var v =  new JU.List ();
var zis =  new java.util.zip.ZipInputStream (bis);
var ze;
var manifest = null;
while ((ze = zis.getNextEntry ()) != null) {
var fileName = ze.getName ();
if (addManifest && J.io2.ZipUtil.isJmolManifest (fileName)) manifest = J.io2.ZipUtil.getStreamAsString (zis);
 else if (!fileName.startsWith ("__MACOS")) v.addLast (fileName);
}
zis.close ();
if (addManifest) v.add (0, manifest == null ? "" : manifest + "\n############\n");
return v.toArray ( new Array (v.size ()));
}, "java.io.BufferedInputStream,~B");
c$.getStreamAsString = $_M(c$, "getStreamAsString", 
function (is) {
return J.io.JmolBinary.fixUTF (J.io.JmolBinary.getStreamBytes (is, -1));
}, "java.io.InputStream");
c$.isJmolManifest = $_M(c$, "isJmolManifest", 
function (thisEntry) {
return thisEntry.startsWith ("JmolManifest");
}, "~S");
$_V(c$, "cacheZipContents", 
function (bis, fileName, cache) {
var zis = this.newZipInputStream (bis);
var ze;
var listing =  new JU.SB ();
var n = 0;
try {
while ((ze = zis.getNextEntry ()) != null) {
var name = ze.getName ();
listing.append (name).appendC ('\n');
var nBytes = ze.getSize ();
var bytes = J.io.JmolBinary.getStreamBytes (zis, nBytes);
n += bytes.length;
cache.put (fileName + "|" + name, bytes);
}
zis.close ();
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
try {
zis.close ();
} catch (e1) {
if (Clazz_exceptionOf (e1, java.io.IOException)) {
} else {
throw e1;
}
}
return null;
} else {
throw e;
}
}
if (n == 0) return null;
J.util.Logger.info ("ZipUtil cached " + n + " bytes from " + fileName);
return listing.toString ();
}, "java.io.BufferedInputStream,~S,java.util.Map");
c$.getUnGzippedInputStream = $_M(c$, "getUnGzippedInputStream", 
function (bytes) {
try {
return J.io.JmolBinary.getUnzippedInputStream (J.io.JmolBinary.getBIS (bytes));
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
return null;
} else {
throw e;
}
}
}, "~A");
$_V(c$, "newGZIPInputStream", 
function (is) {
return  new java.io.BufferedInputStream ( new java.util.zip.GZIPInputStream (is, 512));
}, "java.io.InputStream");
$_V(c$, "getAtomSetCollectionOrBufferedReaderFromZip", 
function (adapter, is, fileName, zipDirectory, htParams, subFilePtr, asBufferedReader) {
var doCombine = (subFilePtr == 1);
htParams.put ("zipSet", fileName);
var subFileList = htParams.get ("subFileList");
if (subFileList == null) subFileList = J.io2.ZipUtil.checkSpecialInZip (zipDirectory);
var subFileName = (subFileList == null || subFilePtr >= subFileList.length ? null : subFileList[subFilePtr]);
if (subFileName != null && (subFileName.startsWith ("/") || subFileName.startsWith ("\\"))) subFileName = subFileName.substring (1);
var selectedFile = 0;
if (subFileName == null && htParams.containsKey ("modelNumber")) {
selectedFile = (htParams.get ("modelNumber")).intValue ();
if (selectedFile > 0 && doCombine) htParams.remove ("modelNumber");
}var manifest = htParams.get ("manifest");
var useFileManifest = (manifest == null);
if (useFileManifest) manifest = (zipDirectory.length > 0 ? zipDirectory[0] : "");
var haveManifest = (manifest.length > 0);
if (haveManifest) {
if (J.util.Logger.debugging) J.util.Logger.debug ("manifest for  " + fileName + ":\n" + manifest);
}var ignoreErrors = (manifest.indexOf ("IGNORE_ERRORS") >= 0);
var selectAll = (manifest.indexOf ("IGNORE_MANIFEST") >= 0);
var exceptFiles = (manifest.indexOf ("EXCEPT_FILES") >= 0);
if (selectAll || subFileName != null) haveManifest = false;
if (useFileManifest && haveManifest) {
var path = J.io.JmolBinary.getManifestScriptPath (manifest);
if (path != null) return "NOTE: file recognized as a script file: " + fileName + path + "\n";
}var vCollections =  new JU.List ();
var htCollections = (haveManifest ?  new java.util.Hashtable () : null);
var nFiles = 0;
var ret = J.io2.ZipUtil.checkSpecialData (is, zipDirectory);
if (Clazz_instanceOf (ret, String)) return ret;
var data = ret;
try {
if (data != null) {
var reader = J.io.JmolBinary.getBR (data.toString ());
if (asBufferedReader) return reader;
ret = adapter.getAtomSetCollectionFromReader (fileName, reader, htParams);
if (Clazz_instanceOf (ret, String)) return ret;
if (Clazz_instanceOf (ret, J.adapter.smarter.AtomSetCollection)) {
var atomSetCollection = ret;
if (atomSetCollection.errorMessage != null) {
if (ignoreErrors) return null;
return atomSetCollection.errorMessage;
}return atomSetCollection;
}if (ignoreErrors) return null;
return "unknown reader error";
}if (Clazz_instanceOf (is, java.io.BufferedInputStream)) is = J.io.JmolBinary.checkPngZipStream (is);
var zis = J.io.JmolBinary.newZipInputStream (is);
var ze;
if (haveManifest) manifest = '|' + manifest.$replace ('\r', '|').$replace ('\n', '|') + '|';
while ((ze = zis.getNextEntry ()) != null && (selectedFile <= 0 || vCollections.size () < selectedFile)) {
if (ze.isDirectory ()) continue;
var thisEntry = ze.getName ();
if (subFileName != null && !thisEntry.equals (subFileName)) continue;
if (subFileName != null) htParams.put ("subFileName", subFileName);
if (J.io2.ZipUtil.isJmolManifest (thisEntry) || haveManifest && exceptFiles == manifest.indexOf ("|" + thisEntry + "|") >= 0) continue;
var bytes = J.io.JmolBinary.getStreamBytes (zis, ze.getSize ());
if (J.io.JmolBinary.isGzipB (bytes)) bytes = J.io.JmolBinary.getStreamBytes (J.io2.ZipUtil.getUnGzippedInputStream (bytes), -1);
if (J.io.JmolBinary.isZipB (bytes)) {
var bis = J.io.JmolBinary.getBIS (bytes);
var zipDir2 = J.io.JmolBinary.getZipDirectoryAndClose (bis, true);
bis = J.io.JmolBinary.getBIS (bytes);
var atomSetCollections = this.getAtomSetCollectionOrBufferedReaderFromZip (adapter, bis, fileName + "|" + thisEntry, zipDir2, htParams, ++subFilePtr, asBufferedReader);
if (Clazz_instanceOf (atomSetCollections, String)) {
if (ignoreErrors) continue;
return atomSetCollections;
} else if (Clazz_instanceOf (atomSetCollections, J.adapter.smarter.AtomSetCollection) || Clazz_instanceOf (atomSetCollections, JU.List)) {
if (haveManifest && !exceptFiles) htCollections.put (thisEntry, atomSetCollections);
 else vCollections.addLast (atomSetCollections);
} else if (Clazz_instanceOf (atomSetCollections, java.io.BufferedReader)) {
if (doCombine) zis.close ();
return atomSetCollections;
} else {
if (ignoreErrors) continue;
zis.close ();
return "unknown zip reader error";
}} else if (J.io.JmolBinary.isPickleB (bytes)) {
var bis = J.io.JmolBinary.getBIS (bytes);
if (doCombine) zis.close ();
return bis;
} else {
var sData;
if (J.io.JmolBinary.isCompoundDocumentB (bytes)) {
var jd = J.api.Interface.getInterface ("jmol.util.CompoundDocument");
jd.setStream (J.io.JmolBinary.getBIS (bytes), true);
sData = jd.getAllDataFiles ("Molecule", "Input").toString ();
} else {
sData = J.io.JmolBinary.fixUTF (bytes);
}var reader = J.io.JmolBinary.getBR (sData);
if (asBufferedReader) {
if (doCombine) zis.close ();
return reader;
}var fname = fileName + "|" + ze.getName ();
ret = adapter.getAtomSetCollectionFromReader (fname, reader, htParams);
if (!(Clazz_instanceOf (ret, J.adapter.smarter.AtomSetCollection))) {
if (ignoreErrors) continue;
zis.close ();
return "" + ret;
}if (haveManifest && !exceptFiles) htCollections.put (thisEntry, ret);
 else vCollections.addLast (ret);
var a = ret;
if (a.errorMessage != null) {
if (ignoreErrors) continue;
zis.close ();
return a.errorMessage;
}}}
if (doCombine) zis.close ();
if (haveManifest && !exceptFiles) {
var list = JU.PT.split (manifest, "|");
for (var i = 0; i < list.length; i++) {
var file = list[i];
if (file.length == 0 || file.indexOf ("#") == 0) continue;
if (htCollections.containsKey (file)) vCollections.addLast (htCollections.get (file));
 else if (J.util.Logger.debugging) J.util.Logger.debug ("manifested file " + file + " was not found in " + fileName);
}
}if (!doCombine) return vCollections;
var result =  new J.adapter.smarter.AtomSetCollection ("Array", null, null, vCollections);
if (result.errorMessage != null) {
if (ignoreErrors) return null;
return result.errorMessage;
}if (nFiles == 1) selectedFile = 1;
if (selectedFile > 0 && selectedFile <= vCollections.size ()) return vCollections.get (selectedFile - 1);
return result;
} catch (e$$) {
if (Clazz_exceptionOf (e$$, Exception)) {
var e = e$$;
{
if (ignoreErrors) return null;
J.util.Logger.error ("" + e);
return "" + e;
}
} else if (Clazz_exceptionOf (e$$, Error)) {
var er = e$$;
{
J.util.Logger.errorEx (null, er);
return "" + er;
}
} else {
throw e$$;
}
}
}, "J.api.JmolAdapter,java.io.InputStream,~S,~A,java.util.Map,~N,~B");
c$.checkSpecialData = $_M(c$, "checkSpecialData", 
function (is, zipDirectory) {
var isSpartan = false;
for (var i = 1; i < zipDirectory.length; i++) {
if (zipDirectory[i].endsWith (".spardir/") || zipDirectory[i].indexOf ("_spartandir") >= 0) {
isSpartan = true;
break;
}}
if (!isSpartan) return null;
var data =  new JU.SB ();
data.append ("Zip File Directory: ").append ("\n").append (J.util.Escape.eAS (zipDirectory, true)).append ("\n");
var fileData =  new java.util.Hashtable ();
J.io2.ZipUtil.getAllZipDataStatic (is, [], "", "Molecule", fileData);
var prefix = "|";
var outputData = fileData.get (prefix + "output");
if (outputData == null) outputData = fileData.get ((prefix = "|" + zipDirectory[1]) + "output");
data.append (outputData);
var files = J.io2.ZipUtil.getSpartanFileList (prefix, J.io2.ZipUtil.getSpartanDirs (outputData));
for (var i = 2; i < files.length; i++) {
var name = files[i];
if (fileData.containsKey (name)) data.append (fileData.get (name));
 else data.append (name + "\n");
}
return data;
}, "java.io.InputStream,~A");
$_V(c$, "spartanFileList", 
function (name, type) {
var dirNums = J.io2.ZipUtil.getSpartanDirs (type);
if (dirNums.length == 0 && name.endsWith (".spardir.zip") && type.indexOf (".zip|output") >= 0) {
var sname = name.$replace ('\\', '/');
var pt = name.lastIndexOf (".spardir");
pt = sname.lastIndexOf ("/");
sname = name + "|" + name.substring (pt + 1, name.length - 4);
return ["SpartanSmol", sname, sname + "/output"];
}return J.io2.ZipUtil.getSpartanFileList (name, dirNums);
}, "~S,~S");
c$.getSpartanDirs = $_M(c$, "getSpartanDirs", 
function (outputFileData) {
if (outputFileData == null) return [];
if (outputFileData.startsWith ("java.io.FileNotFoundException") || outputFileData.startsWith ("FILE NOT FOUND") || outputFileData.indexOf ("<html") >= 0) return ["M0001"];
var v =  new JU.List ();
var token;
var lasttoken = "";
try {
var tokens =  new java.util.StringTokenizer (outputFileData, " \t\r\n");
while (tokens.hasMoreTokens ()) {
if ((token = tokens.nextToken ()).equals (")")) v.addLast (lasttoken);
 else if (token.equals ("Start-") && tokens.nextToken ().equals ("Molecule")) v.addLast (JU.PT.split (tokens.nextToken (), "\"")[1]);
lasttoken = token;
}
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
} else {
throw e;
}
}
return v.toArray ( new Array (v.size ()));
}, "~S");
c$.getSpartanFileList = $_M(c$, "getSpartanFileList", 
function (name, dirNums) {
var files =  new Array (2 + dirNums.length * 5);
files[0] = "SpartanSmol";
files[1] = "Directory Entry ";
var pt = 2;
name = name.$replace ('\\', '/');
if (name.endsWith ("/")) name = name.substring (0, name.length - 1);
for (var i = 0; i < dirNums.length; i++) {
var path = name + (Character.isDigit (dirNums[i].charAt (0)) ? "/Profile." + dirNums[i] : "/" + dirNums[i]);
files[pt++] = path + "/#JMOL_MODEL " + dirNums[i];
files[pt++] = path + "/input";
files[pt++] = path + "/archive";
files[pt++] = path + "/Molecule:asBinaryString";
files[pt++] = path + "/proparc";
}
return files;
}, "~S,~A");
c$.checkSpecialInZip = $_M(c$, "checkSpecialInZip", 
function (zipDirectory) {
var name;
return (zipDirectory.length < 2 ? null : (name = zipDirectory[1]).endsWith (".spardir/") || zipDirectory.length == 2 ? ["", (name.endsWith ("/") ? name.substring (0, name.length - 1) : name)] : null);
}, "~A");
$_V(c$, "getCachedPngjBytes", 
function (fm, pathName) {
if (pathName.indexOf (".png") < 0) return null;
J.util.Logger.info ("FileManager checking PNGJ cache for " + pathName);
var shortName = J.io2.ZipUtil.shortSceneFilename (pathName);
if (fm.pngjCache == null && !this.cachePngjFile (fm, [pathName, null])) return null;
var pngjCache = fm.pngjCache;
var isMin = (pathName.indexOf (".min.") >= 0);
if (!isMin) {
var cName = fm.getCanonicalName (J.io.JmolBinary.getZipRoot (pathName));
if (!pngjCache.containsKey (cName) && !this.cachePngjFile (fm, [pathName, null])) return null;
if (pathName.indexOf ("|") < 0) shortName = cName;
}if (pngjCache.containsKey (shortName)) {
J.util.Logger.info ("FileManager using memory cache " + shortName);
return pngjCache.get (shortName);
}if (!isMin || !this.cachePngjFile (fm, [pathName, null])) return null;
J.util.Logger.info ("FileManager using memory cache " + shortName);
return pngjCache.get (shortName);
}, "J.viewer.FileManager,~S");
$_V(c$, "cachePngjFile", 
function (fm, data) {
var pngjCache = fm.pngjCache =  new java.util.Hashtable ();
if (data == null) return false;
data[1] = null;
if (data[0] == null) return false;
data[0] = J.io.JmolBinary.getZipRoot (data[0]);
var shortName = J.io2.ZipUtil.shortSceneFilename (data[0]);
try {
data[1] = this.cacheZipContents (J.io.JmolBinary.checkPngZipStream (fm.getBufferedInputStreamOrErrorMessageFromName (data[0], null, false, false, null, false)), shortName, fm.pngjCache);
} catch (e) {
if (Clazz_exceptionOf (e, Exception)) {
return false;
} else {
throw e;
}
}
if (data[1] == null) return false;
var bytes = data[1].getBytes ();
pngjCache.put (fm.getCanonicalName (data[0]), bytes);
if (shortName.indexOf ("_scene_") >= 0) {
pngjCache.put (J.io2.ZipUtil.shortSceneFilename (data[0]), bytes);
bytes = pngjCache.remove (shortName + "|state.spt");
if (bytes != null) pngjCache.put (J.io2.ZipUtil.shortSceneFilename (data[0] + "|state.spt"), bytes);
}for (var key, $key = pngjCache.keySet ().iterator (); $key.hasNext () && ((key = $key.next ()) || true);) System.out.println (key);

return true;
}, "J.viewer.FileManager,~A");
c$.shortSceneFilename = $_M(c$, "shortSceneFilename", 
function (pathName) {
var pt = pathName.indexOf ("_scene_") + 7;
if (pt < 7) return pathName;
var s = "";
if (pathName.endsWith ("|state.spt")) {
var pt1 = pathName.indexOf ('.', pt);
if (pt1 < 0) return pathName;
s = pathName.substring (pt, pt1);
}var pt2 = pathName.lastIndexOf ("|");
return pathName.substring (0, pt) + s + (pt2 > 0 ? pathName.substring (pt2) : "");
}, "~S");
$_V(c$, "addZipEntry", 
function (zos, fileName) {
(zos).putNextEntry ( new java.util.zip.ZipEntry (fileName));
}, "~O,~S");
$_V(c$, "closeZipEntry", 
function (zos) {
(zos).closeEntry ();
}, "~O");
$_V(c$, "getZipOutputStream", 
function (bos) {
{
return J.api.Interface.getInterface(
"java.util.zip.ZipOutputStream").setZOS(bos);
}}, "~O");
$_V(c$, "getCrcValue", 
function (bytes) {
var crc =  new java.util.zip.CRC32 ();
crc.update (bytes, 0, bytes.length);
return crc.getValue ();
}, "~A");
});
$_L(["java.io.Closeable","$.InputStream"],"java.io.FileInputStream",["java.lang.IndexOutOfBoundsException","$.NullPointerException"],function(){
c$=$_C(function(){
this.fd=null;
this.innerFD=false;
$_Z(this,arguments);
},java.io,"FileInputStream",java.io.InputStream,java.io.Closeable);
$_K(c$,
function(file){
$_R(this,java.io.FileInputStream);
},"java.io.File");
$_K(c$,
function(fd){
$_R(this,java.io.FileInputStream);
if(fd==null){
throw new NullPointerException();
}},"java.io.FileDescriptor");
$_K(c$,
function(fileName){
this.construct(null==fileName?null:null);
},"~S");
$_V(c$,"available",
function(){
return 0;
});
$_V(c$,"close",
function(){
if(this.fd==null){
return;
}});
$_V(c$,"finalize",
function(){
this.close();
});
$_M(c$,"getFD",
function(){
return this.fd;
});
$_M(c$,"read",
function(){
var readed=$_A(1,0);
var result=this.read(readed,0,1);
return result==-1?-1:readed[0]&0xff;
});
$_M(c$,"read",
function(buffer){
return this.read(buffer,0,buffer.length);
},"~A");
$_M(c$,"read",
function(buffer,offset,count){
if(count>buffer.length-offset||count<0||offset<0){
throw new IndexOutOfBoundsException();
}if(0==count){
return 0;
}return 0;
},"~A,~N,~N");
$_V(c$,"skip",
function(count){
return 0;
},"~N");
});
Clazz_declarePackage ("JZ");
Clazz_declareInterface (JZ, "Checksum");
Clazz_declarePackage ("JZ");
Clazz_load (["JZ.Checksum"], "JZ.CRC32", null, function () {
c$ = Clazz_decorateAsClass (function () {
this.crc = 0;
this.b1 = null;
Clazz_instantialize (this, arguments);
}, JZ, "CRC32", null, JZ.Checksum);
Clazz_prepareFields (c$, function () {
this.b1 =  Clazz_newByteArray (1, 0);
});
$_V(c$, "update", 
function (buf, index, len) {
var c = ~this.crc;
while (--len >= 0) c = JZ.CRC32.crc_table[(c ^ buf[index++]) & 0xff] ^ (c >>> 8);

this.crc = ~c;
}, "~A,~N,~N");
$_V(c$, "reset", 
function () {
this.crc = 0;
});
$_V(c$, "resetLong", 
function (vv) {
this.crc = (vv & 0xffffffff);
}, "~N");
$_V(c$, "getValue", 
function () {
return this.crc & 0xffffffff;
});
$_V(c$, "updateByteAsInt", 
function (b) {
this.b1[0] = b;
this.update (this.b1, 0, 1);
}, "~N");
Clazz_defineStatics (c$,
"crc_table", [0, 1996959894, -301047508, -1727442502, 124634137, 1886057615, -379345611, -1637575261, 249268274, 2044508324, -522852066, -1747789432, 162941995, 2125561021, -407360249, -1866523247, 498536548, 1789927666, -205950648, -2067906082, 450548861, 1843258603, -187386543, -2083289657, 325883990, 1684777152, -43845254, -1973040660, 335633487, 1661365465, -99664541, -1928851979, 997073096, 1281953886, -715111964, -1570279054, 1006888145, 1258607687, -770865667, -1526024853, 901097722, 1119000684, -608450090, -1396901568, 853044451, 1172266101, -589951537, -1412350631, 651767980, 1373503546, -925412992, -1076862698, 565507253, 1454621731, -809855591, -1195530993, 671266974, 1594198024, -972236366, -1324619484, 795835527, 1483230225, -1050600021, -1234817731, 1994146192, 31158534, -1731059524, -271249366, 1907459465, 112637215, -1614814043, -390540237, 2013776290, 251722036, -1777751922, -519137256, 2137656763, 141376813, -1855689577, -429695999, 1802195444, 476864866, -2056965928, -228458418, 1812370925, 453092731, -2113342271, -183516073, 1706088902, 314042704, -1950435094, -54949764, 1658658271, 366619977, -1932296973, -69972891, 1303535960, 984961486, -1547960204, -725929758, 1256170817, 1037604311, -1529756563, -740887301, 1131014506, 879679996, -1385723834, -631195440, 1141124467, 855842277, -1442165665, -586318647, 1342533948, 654459306, -1106571248, -921952122, 1466479909, 544179635, -1184443383, -832445281, 1591671054, 702138776, -1328506846, -942167884, 1504918807, 783551873, -1212326853, -1061524307, -306674912, -1698712650, 62317068, 1957810842, -355121351, -1647151185, 81470997, 1943803523, -480048366, -1805370492, 225274430, 2053790376, -468791541, -1828061283, 167816743, 2097651377, -267414716, -2029476910, 503444072, 1762050814, -144550051, -2140837941, 426522225, 1852507879, -19653770, -1982649376, 282753626, 1742555852, -105259153, -1900089351, 397917763, 1622183637, -690576408, -1580100738, 953729732, 1340076626, -776247311, -1497606297, 1068828381, 1219638859, -670225446, -1358292148, 906185462, 1090812512, -547295293, -1469587627, 829329135, 1181335161, -882789492, -1134132454, 628085408, 1382605366, -871598187, -1156888829, 570562233, 1426400815, -977650754, -1296233688, 733239954, 1555261956, -1026031705, -1244606671, 752459403, 1541320221, -1687895376, -328994266, 1969922972, 40735498, -1677130071, -351390145, 1913087877, 83908371, -1782625662, -491226604, 2075208622, 213261112, -1831694693, -438977011, 2094854071, 198958881, -2032938284, -237706686, 1759359992, 534414190, -2118248755, -155638181, 1873836001, 414664567, -2012718362, -15766928, 1711684554, 285281116, -1889165569, -127750551, 1634467795, 376229701, -1609899400, -686959890, 1308918612, 956543938, -1486412191, -799009033, 1231636301, 1047427035, -1362007478, -640263460, 1088359270, 936918000, -1447252397, -558129467, 1202900863, 817233897, -1111625188, -893730166, 1404277552, 615818150, -1160759803, -841546093, 1423857449, 601450431, -1285129682, -1000256840, 1567103746, 711928724, -1274298825, -1022587231, 1510334235, 755167117]);
});
Clazz_declarePackage ("java.util.zip");
Clazz_load (["JZ.CRC32"], "java.util.zip.CRC32", null, function () {
c$ = Clazz_declareType (java.util.zip, "CRC32", JZ.CRC32);
});
Clazz_declarePackage ("JZ");
Clazz_load (["java.io.FilterInputStream"], "JZ.InflaterInputStream", ["java.io.EOFException", "$.IOException", "java.lang.IllegalArgumentException", "$.IndexOutOfBoundsException", "$.NullPointerException"], function () {
c$ = Clazz_decorateAsClass (function () {
this.inflater = null;
this.buf = null;
this.len = 0;
this.closed = false;
this.eof = false;
this.close_in = true;
this.myinflater = false;
this.byte1 = null;
this.b = null;
Clazz_instantialize (this, arguments);
}, JZ, "InflaterInputStream", java.io.FilterInputStream);
Clazz_prepareFields (c$, function () {
this.byte1 =  Clazz_newByteArray (1, 0);
this.b =  Clazz_newByteArray (512, 0);
});
Clazz_makeConstructor (c$, 
function ($in, inflater, size, close_in) {
Clazz_superConstructor (this, JZ.InflaterInputStream, [$in]);
this.inflater = inflater;
this.buf =  Clazz_newByteArray (size, 0);
this.close_in = close_in;
}, "java.io.InputStream,JZ.Inflater,~N,~B");
$_V(c$, "readByteAsInt", 
function () {
if (this.closed) {
throw  new java.io.IOException ("Stream closed");
}return this.read (this.byte1, 0, 1) == -1 ? -1 : this.byte1[0] & 0xff;
});
$_V(c$, "read", 
function (b, off, len) {
return this.readInf (b, off, len);
}, "~A,~N,~N");
$_M(c$, "readInf", 
function (b, off, len) {
if (this.closed) {
throw  new java.io.IOException ("Stream closed");
}if (b == null) {
throw  new NullPointerException ();
} else if (off < 0 || len < 0 || len > b.length - off) {
throw  new IndexOutOfBoundsException ();
} else if (len == 0) {
return 0;
} else if (this.eof) {
return -1;
}var n = 0;
this.inflater.setOutput (b, off, len);
while (!this.eof) {
if (this.inflater.avail_in == 0) this.fill ();
var err = this.inflater.inflate (0);
n += this.inflater.next_out_index - off;
off = this.inflater.next_out_index;
switch (err) {
case -3:
throw  new java.io.IOException (this.inflater.msg);
case 1:
case 2:
this.eof = true;
if (err == 2) return -1;
break;
default:
}
if (this.inflater.avail_out == 0) break;
}
return n;
}, "~A,~N,~N");
$_V(c$, "available", 
function () {
if (this.closed) {
throw  new java.io.IOException ("Stream closed");
}return (this.eof ? 0 : 1);
});
$_V(c$, "skip", 
function (n) {
if (n < 0) {
throw  new IllegalArgumentException ("negative skip length");
}if (this.closed) {
throw  new java.io.IOException ("Stream closed");
}var max = Math.min (n, 2147483647);
var total = 0;
while (total < max) {
var len = max - total;
if (len > this.b.length) {
len = this.b.length;
}len = this.read (this.b, 0, len);
if (len == -1) {
this.eof = true;
break;
}total += len;
}
return total;
}, "~N");
$_V(c$, "close", 
function () {
if (!this.closed) {
if (this.myinflater) this.inflater.end ();
if (this.close_in) this.$in.close ();
this.closed = true;
}});
$_M(c$, "fill", 
function () {
if (this.closed) {
throw  new java.io.IOException ("Stream closed");
}this.len = this.$in.read (this.buf, 0, this.buf.length);
if (this.len == -1) {
if (this.inflater.istate.wrap == 0 && !this.inflater.finished ()) {
this.buf[0] = 0;
this.len = 1;
} else if (this.inflater.istate.was != -1) {
throw  new java.io.IOException ("footer is not found");
} else {
throw  new java.io.EOFException ("Unexpected end of ZLIB input stream");
}}this.inflater.setInput (this.buf, 0, this.len, true);
});
$_V(c$, "markSupported", 
function () {
return false;
});
$_V(c$, "mark", 
function (readlimit) {
}, "~N");
$_V(c$, "reset", 
function () {
throw  new java.io.IOException ("mark/reset not supported");
});
$_M(c$, "getTotalIn", 
function () {
return this.inflater.getTotalIn ();
});
$_M(c$, "getTotalOut", 
function () {
return this.inflater.getTotalOut ();
});
$_M(c$, "getAvailIn", 
function () {
if (this.inflater.avail_in <= 0) return null;
var tmp =  Clazz_newByteArray (this.inflater.avail_in, 0);
System.arraycopy (this.inflater.next_in, this.inflater.next_in_index, tmp, 0, this.inflater.avail_in);
return tmp;
});
$_M(c$, "readHeader", 
function () {
var empty = "".getBytes ();
this.inflater.setInput (empty, 0, 0, false);
this.inflater.setOutput (empty, 0, 0);
var err = this.inflater.inflate (0);
if (!this.inflater.istate.inParsingHeader ()) {
return;
}var b1 =  Clazz_newByteArray (1, 0);
do {
var i = this.$in.read (b1, 0, 1);
if (i <= 0) throw  new java.io.IOException ("no input");
this.inflater.setInput (b1, 0, b1.length, false);
err = this.inflater.inflate (0);
if (err != 0) throw  new java.io.IOException (this.inflater.msg);
} while (this.inflater.istate.inParsingHeader ());
});
$_M(c$, "getInflater", 
function () {
return this.inflater;
});
Clazz_defineStatics (c$,
"DEFAULT_BUFSIZE", 512);
});
Clazz_declarePackage ("java.util.zip");
Clazz_load (["JZ.InflaterInputStream"], "java.util.zip.InflaterInputStream", null, function () {
c$ = Clazz_decorateAsClass (function () {
this.inf = null;
Clazz_instantialize (this, arguments);
}, java.util.zip, "InflaterInputStream", JZ.InflaterInputStream);
Clazz_makeConstructor (c$, 
function ($in, inflater, size) {
Clazz_superConstructor (this, java.util.zip.InflaterInputStream, [$in, inflater, size, true]);
this.inf = inflater;
}, "java.io.InputStream,java.util.zip.Inflater,~N");
});
Clazz_declarePackage ("java.util.zip");
Clazz_load (["java.util.zip.InflaterInputStream", "$.CRC32"], "java.util.zip.GZIPInputStream", ["java.io.EOFException", "$.IOException", "java.util.zip.CheckedInputStream", "$.Inflater", "$.ZipException"], function () {
c$ = Clazz_decorateAsClass (function () {
this.crc = null;
this.eos = false;
this.$closed = false;
this.tmpbuf = null;
Clazz_instantialize (this, arguments);
}, java.util.zip, "GZIPInputStream", java.util.zip.InflaterInputStream);
Clazz_prepareFields (c$, function () {
this.crc =  new java.util.zip.CRC32 ();
this.tmpbuf =  Clazz_newByteArray (128, 0);
});
$_M(c$, "ensureOpen", 
function () {
if (this.$closed) {
throw  new java.io.IOException ("Stream closed");
}});
Clazz_makeConstructor (c$, 
function ($in, size) {
Clazz_superConstructor (this, java.util.zip.GZIPInputStream, [$in,  new java.util.zip.Inflater ().init (0, true), size]);
this.readHeader ($in);
}, "java.io.InputStream,~N");
$_V(c$, "read", 
function (buf, off, len) {
this.ensureOpen ();
if (this.eos) {
return -1;
}var n = this.readInf (buf, off, len);
if (n == -1) {
if (this.readTrailer ()) this.eos = true;
 else return this.read (buf, off, len);
} else {
this.crc.update (buf, off, n);
}return n;
}, "~A,~N,~N");
$_M(c$, "close", 
function () {
if (!this.$closed) {
Clazz_superCall (this, java.util.zip.GZIPInputStream, "close", []);
this.eos = true;
this.$closed = true;
}});
$_M(c$, "readHeader", 
function (this_in) {
var $in =  new java.util.zip.CheckedInputStream (this_in, this.crc);
this.crc.reset ();
if (this.readUShort ($in) != 35615) {
throw  new java.util.zip.ZipException ("Not in GZIP format");
}if (this.readUByte ($in) != 8) {
throw  new java.util.zip.ZipException ("Unsupported compression method");
}var flg = this.readUByte ($in);
this.skipBytes ($in, 6);
var n = 10;
if ((flg & 4) == 4) {
var m = this.readUShort ($in);
this.skipBytes ($in, m);
n += m + 2;
}if ((flg & 8) == 8) {
do {
n++;
} while (this.readUByte ($in) != 0);
}if ((flg & 16) == 16) {
do {
n++;
} while (this.readUByte ($in) != 0);
}if ((flg & 2) == 2) {
var v = this.crc.getValue () & 0xffff;
if (this.readUShort ($in) != v) {
throw  new java.util.zip.ZipException ("Corrupt GZIP header");
}n += 2;
}this.crc.reset ();
return n;
}, "java.io.InputStream");
$_M(c$, "readTrailer", 
function () {
return true;
});
$_M(c$, "readUShort", 
function ($in) {
var b = this.readUByte ($in);
return (this.readUByte ($in) << 8) | b;
}, "java.io.InputStream");
$_M(c$, "readUByte", 
function ($in) {
var b = $in.readByteAsInt ();
if (b == -1) {
throw  new java.io.EOFException ();
}if (b < -1 || b > 255) {
throw  new java.io.IOException (this.$in.getClass ().getName () + ".read() returned value out of range -1..255: " + b);
}return b;
}, "java.io.InputStream");
$_M(c$, "skipBytes", 
function ($in, n) {
while (n > 0) {
var len = $in.read (this.tmpbuf, 0, n < this.tmpbuf.length ? n : this.tmpbuf.length);
if (len == -1) {
throw  new java.io.EOFException ();
}n -= len;
}
}, "java.io.InputStream,~N");
Clazz_defineStatics (c$,
"GZIP_MAGIC", 0x8b1f,
"FHCRC", 2,
"FEXTRA", 4,
"FNAME", 8,
"FCOMMENT", 16);
});
Clazz_declarePackage ("JZ");
Clazz_load (null, "JZ.ZStream", ["JZ.Adler32"], function () {
c$ = Clazz_decorateAsClass (function () {
this.next_in = null;
this.next_in_index = 0;
this.avail_in = 0;
this.total_in = 0;
this.next_out = null;
this.next_out_index = 0;
this.avail_out = 0;
this.total_out = 0;
this.msg = null;
this.dstate = null;
this.istate = null;
this.data_type = 0;
this.checksum = null;
Clazz_instantialize (this, arguments);
}, JZ, "ZStream");
$_M(c$, "setAdler32", 
function () {
this.checksum =  new JZ.Adler32 ();
});
$_M(c$, "inflate", 
function (f) {
if (this.istate == null) return -2;
return this.istate.inflate (f);
}, "~N");
$_M(c$, "deflate", 
function (flush) {
if (this.dstate == null) {
return -2;
}return this.dstate.deflate (flush);
}, "~N");
$_M(c$, "flush_pending", 
function () {
var len = this.dstate.pending;
if (len > this.avail_out) len = this.avail_out;
if (len == 0) return;
System.arraycopy (this.dstate.pending_buf, this.dstate.pending_out, this.next_out, this.next_out_index, len);
this.next_out_index += len;
this.dstate.pending_out += len;
this.total_out += len;
this.avail_out -= len;
this.dstate.pending -= len;
if (this.dstate.pending == 0) {
this.dstate.pending_out = 0;
}});
$_M(c$, "read_buf", 
function (buf, start, size) {
var len = this.avail_in;
if (len > size) len = size;
if (len == 0) return 0;
this.avail_in -= len;
if (this.dstate.wrap != 0) {
this.checksum.update (this.next_in, this.next_in_index, len);
}System.arraycopy (this.next_in, this.next_in_index, buf, start, len);
this.next_in_index += len;
this.total_in += len;
return len;
}, "~A,~N,~N");
$_M(c$, "getAdler", 
function () {
return this.checksum.getValue ();
});
$_M(c$, "free", 
function () {
this.next_in = null;
this.next_out = null;
this.msg = null;
});
$_M(c$, "setOutput", 
function (buf, off, len) {
this.next_out = buf;
this.next_out_index = off;
this.avail_out = len;
}, "~A,~N,~N");
$_M(c$, "setInput", 
function (buf, off, len, append) {
if (len <= 0 && append && this.next_in != null) return;
if (this.avail_in > 0 && append) {
var tmp =  Clazz_newByteArray (this.avail_in + len, 0);
System.arraycopy (this.next_in, this.next_in_index, tmp, 0, this.avail_in);
System.arraycopy (buf, off, tmp, this.avail_in, len);
this.next_in = tmp;
this.next_in_index = 0;
this.avail_in += len;
} else {
this.next_in = buf;
this.next_in_index = off;
this.avail_in = len;
}}, "~A,~N,~N,~B");
$_M(c$, "getAvailIn", 
function () {
return this.avail_in;
});
$_M(c$, "getTotalOut", 
function () {
return this.total_out;
});
$_M(c$, "getTotalIn", 
function () {
return this.total_in;
});
c$.getBytes = $_M(c$, "getBytes", 
function (s) {
{
var x = [];
for (var i = 0; i < s.length;i++) {
var pt = s.charCodeAt(i);
if (pt <= 0x7F) {
x.push(pt);
} else if (pt <= 0x7FF) {
x.push(0xC0|((pt>>6)&0x1F));
x.push(0x80|(pt&0x3F));
} else if (pt <= 0xFFFF) {
x.push(0xE0|((pt>>12)&0xF));
x.push(0x80|((pt>>6)&0x3F));
x.push(0x80|(pt&0x3F));
} else {
x.push(0x3F); // '?'
}
}
return (Int32Array != Array ? new Int32Array(x) : x);
}}, "~S");
Clazz_defineStatics (c$,
"Z_STREAM_ERROR", -2);
});
Clazz_declarePackage ("JZ");
Clazz_load (["JZ.ZStream"], "JZ.Inflater", ["JZ.Inflate"], function () {
c$ = Clazz_declareType (JZ, "Inflater", JZ.ZStream);
$_M(c$, "init", 
function (w, nowrap) {
this.setAdler32 ();
if (w == 0) w = 15;
this.istate =  new JZ.Inflate (this);
this.istate.inflateInit (nowrap ? -w : w);
return this;
}, "~N,~B");
$_V(c$, "inflate", 
function (f) {
if (this.istate == null) return -2;
var ret = this.istate.inflate (f);
return ret;
}, "~N");
$_V(c$, "end", 
function () {
if (this.istate == null) return -2;
var ret = this.istate.inflateEnd ();
return ret;
});
$_M(c$, "sync", 
function () {
if (this.istate == null) return -2;
return this.istate.inflateSync ();
});
$_M(c$, "syncPoint", 
function () {
if (this.istate == null) return -2;
return this.istate.inflateSyncPoint ();
});
$_M(c$, "setDictionary", 
function (dictionary, dictLength) {
if (this.istate == null) return -2;
return this.istate.inflateSetDictionary (dictionary, dictLength);
}, "~A,~N");
$_V(c$, "finished", 
function () {
return this.istate.mode == 12;
});
$_M(c$, "reset", 
function () {
this.avail_in = 0;
if (this.istate != null) this.istate.reset ();
});
Clazz_defineStatics (c$,
"MAX_WBITS", 15,
"DEF_WBITS", 15,
"$Z_STREAM_ERROR", -2);
});
Clazz_declarePackage ("JZ");
Clazz_load (["JZ.Checksum"], "JZ.Adler32", null, function () {
c$ = Clazz_decorateAsClass (function () {
this.s1 = 1;
this.s2 = 0;
this.b1 = null;
Clazz_instantialize (this, arguments);
}, JZ, "Adler32", null, JZ.Checksum);
Clazz_prepareFields (c$, function () {
this.b1 =  Clazz_newByteArray (1, 0);
});
$_V(c$, "resetLong", 
function (init) {
this.s1 = init & 0xffff;
this.s2 = (init >> 16) & 0xffff;
}, "~N");
$_V(c$, "reset", 
function () {
this.s1 = 1;
this.s2 = 0;
});
$_V(c$, "getValue", 
function () {
return ((this.s2 << 16) | this.s1);
});
$_V(c$, "update", 
function (buf, index, len) {
if (len == 1) {
this.s1 += buf[index++] & 0xff;
this.s2 += this.s1;
this.s1 %= 65521;
this.s2 %= 65521;
return;
}var len1 = Clazz_doubleToInt (len / 5552);
var len2 = len % 5552;
while (len1-- > 0) {
var k = 5552;
len -= k;
while (k-- > 0) {
this.s1 += buf[index++] & 0xff;
this.s2 += this.s1;
}
this.s1 %= 65521;
this.s2 %= 65521;
}
var k = len2;
len -= k;
while (k-- > 0) {
this.s1 += buf[index++] & 0xff;
this.s2 += this.s1;
}
this.s1 %= 65521;
this.s2 %= 65521;
}, "~A,~N,~N");
$_V(c$, "updateByteAsInt", 
function (b) {
this.b1[0] = b;
this.update (this.b1, 0, 1);
}, "~N");
Clazz_defineStatics (c$,
"BASE", 65521,
"NMAX", 5552);
});
Clazz_declarePackage ("JZ");
c$ = Clazz_decorateAsClass (function () {
this.dyn_tree = null;
this.max_code = 0;
this.stat_desc = null;
Clazz_instantialize (this, arguments);
}, JZ, "Tree");
c$.d_code = $_M(c$, "d_code", 
function (dist) {
return ((dist) < 256 ? JZ.Tree._dist_code[dist] : JZ.Tree._dist_code[256 + ((dist) >>> 7)]);
}, "~N");
$_M(c$, "gen_bitlen", 
function (s) {
var tree = this.dyn_tree;
var stree = this.stat_desc.static_tree;
var extra = this.stat_desc.extra_bits;
var base = this.stat_desc.extra_base;
var max_length = this.stat_desc.max_length;
var h;
var n;
var m;
var bits;
var xbits;
var f;
var overflow = 0;
for (bits = 0; bits <= 15; bits++) s.bl_count[bits] = 0;

tree[s.heap[s.heap_max] * 2 + 1] = 0;
for (h = s.heap_max + 1; h < 573; h++) {
n = s.heap[h];
bits = tree[tree[n * 2 + 1] * 2 + 1] + 1;
if (bits > max_length) {
bits = max_length;
overflow++;
}tree[n * 2 + 1] = bits;
if (n > this.max_code) continue;
s.bl_count[bits]++;
xbits = 0;
if (n >= base) xbits = extra[n - base];
f = tree[n * 2];
s.opt_len += f * (bits + xbits);
if (stree != null) s.static_len += f * (stree[n * 2 + 1] + xbits);
}
if (overflow == 0) return;
do {
bits = max_length - 1;
while (s.bl_count[bits] == 0) bits--;

s.bl_count[bits]--;
s.bl_count[bits + 1] += 2;
s.bl_count[max_length]--;
overflow -= 2;
} while (overflow > 0);
for (bits = max_length; bits != 0; bits--) {
n = s.bl_count[bits];
while (n != 0) {
m = s.heap[--h];
if (m > this.max_code) continue;
if (tree[m * 2 + 1] != bits) {
s.opt_len += (bits - tree[m * 2 + 1]) * tree[m * 2];
tree[m * 2 + 1] = bits;
}n--;
}
}
}, "JZ.Deflate");
$_M(c$, "build_tree", 
function (s) {
var tree = this.dyn_tree;
var stree = this.stat_desc.static_tree;
var elems = this.stat_desc.elems;
var n;
var m;
var max_code = -1;
var node;
s.heap_len = 0;
s.heap_max = 573;
for (n = 0; n < elems; n++) {
if (tree[n * 2] != 0) {
s.heap[++s.heap_len] = max_code = n;
s.depth[n] = 0;
} else {
tree[n * 2 + 1] = 0;
}}
while (s.heap_len < 2) {
node = s.heap[++s.heap_len] = (max_code < 2 ? ++max_code : 0);
tree[node * 2] = 1;
s.depth[node] = 0;
s.opt_len--;
if (stree != null) s.static_len -= stree[node * 2 + 1];
}
this.max_code = max_code;
for (n = Clazz_doubleToInt (s.heap_len / 2); n >= 1; n--) s.pqdownheap (tree, n);

node = elems;
do {
n = s.heap[1];
s.heap[1] = s.heap[s.heap_len--];
s.pqdownheap (tree, 1);
m = s.heap[1];
s.heap[--s.heap_max] = n;
s.heap[--s.heap_max] = m;
tree[node * 2] = (tree[n * 2] + tree[m * 2]);
s.depth[node] = (Math.max (s.depth[n], s.depth[m]) + 1);
tree[n * 2 + 1] = tree[m * 2 + 1] = node;
s.heap[1] = node++;
s.pqdownheap (tree, 1);
} while (s.heap_len >= 2);
s.heap[--s.heap_max] = s.heap[1];
this.gen_bitlen (s);
JZ.Tree.gen_codes (tree, max_code, s.bl_count);
}, "JZ.Deflate");
c$.gen_codes = $_M(c$, "gen_codes", 
function (tree, max_code, bl_count) {
var code = 0;
var bits;
var n;
JZ.Tree.next_code[0] = 0;
for (bits = 1; bits <= 15; bits++) {
JZ.Tree.next_code[bits] = code = ((code + bl_count[bits - 1]) << 1);
}
for (n = 0; n <= max_code; n++) {
var len = tree[n * 2 + 1];
if (len == 0) continue;
tree[n * 2] = (JZ.Tree.bi_reverse (JZ.Tree.next_code[len]++, len));
}
}, "~A,~N,~A");
c$.bi_reverse = $_M(c$, "bi_reverse", 
function (code, len) {
var res = 0;
do {
res |= code & 1;
code >>>= 1;
res <<= 1;
} while (--len > 0);
return res >>> 1;
}, "~N,~N");
Clazz_defineStatics (c$,
"MAX_BITS", 15,
"LITERALS", 256,
"LENGTH_CODES", 29,
"L_CODES", (286),
"HEAP_SIZE", (573),
"MAX_BL_BITS", 7,
"END_BLOCK", 256,
"REP_3_6", 16,
"REPZ_3_10", 17,
"REPZ_11_138", 18,
"extra_lbits", [0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 0],
"extra_dbits", [0, 0, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 6, 7, 7, 8, 8, 9, 9, 10, 10, 11, 11, 12, 12, 13, 13],
"extra_blbits", [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 3, 7],
"bl_order", [16, 17, 18, 0, 8, 7, 9, 6, 10, 5, 11, 4, 12, 3, 13, 2, 14, 1, 15],
"Buf_size", 16,
"DIST_CODE_LEN", 512,
"_dist_code", [0, 1, 2, 3, 4, 4, 5, 5, 6, 6, 6, 6, 7, 7, 7, 7, 8, 8, 8, 8, 8, 8, 8, 8, 9, 9, 9, 9, 9, 9, 9, 9, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 14, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 0, 0, 16, 17, 18, 18, 19, 19, 20, 20, 20, 20, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22, 22, 22, 23, 23, 23, 23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 28, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29, 29],
"_length_code", [0, 1, 2, 3, 4, 5, 6, 7, 8, 8, 9, 9, 10, 10, 11, 11, 12, 12, 12, 12, 13, 13, 13, 13, 14, 14, 14, 14, 15, 15, 15, 15, 16, 16, 16, 16, 16, 16, 16, 16, 17, 17, 17, 17, 17, 17, 17, 17, 18, 18, 18, 18, 18, 18, 18, 18, 19, 19, 19, 19, 19, 19, 19, 19, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 21, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 22, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 23, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 24, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 25, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 26, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 27, 28],
"base_length", [0, 1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 14, 16, 20, 24, 28, 32, 40, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 0],
"base_dist", [0, 1, 2, 3, 4, 6, 8, 12, 16, 24, 32, 48, 64, 96, 128, 192, 256, 384, 512, 768, 1024, 1536, 2048, 3072, 4096, 6144, 8192, 12288, 16384, 24576],
"next_code",  Clazz_newShortArray (16, 0));
Clazz_declarePackage ("JZ");
Clazz_load (["JZ.Tree"], "JZ.StaticTree", null, function () {
c$ = Clazz_decorateAsClass (function () {
this.static_tree = null;
this.extra_bits = null;
this.extra_base = 0;
this.elems = 0;
this.max_length = 0;
Clazz_instantialize (this, arguments);
}, JZ, "StaticTree");
Clazz_makeConstructor (c$, 
function (static_tree, extra_bits, extra_base, elems, max_length) {
this.static_tree = static_tree;
this.extra_bits = extra_bits;
this.extra_base = extra_base;
this.elems = elems;
this.max_length = max_length;
}, "~A,~A,~N,~N,~N");
Clazz_defineStatics (c$,
"MAX_BITS", 15,
"BL_CODES", 19,
"D_CODES", 30,
"LITERALS", 256,
"LENGTH_CODES", 29,
"L_CODES", (286),
"MAX_BL_BITS", 7,
"static_ltree", [12, 8, 140, 8, 76, 8, 204, 8, 44, 8, 172, 8, 108, 8, 236, 8, 28, 8, 156, 8, 92, 8, 220, 8, 60, 8, 188, 8, 124, 8, 252, 8, 2, 8, 130, 8, 66, 8, 194, 8, 34, 8, 162, 8, 98, 8, 226, 8, 18, 8, 146, 8, 82, 8, 210, 8, 50, 8, 178, 8, 114, 8, 242, 8, 10, 8, 138, 8, 74, 8, 202, 8, 42, 8, 170, 8, 106, 8, 234, 8, 26, 8, 154, 8, 90, 8, 218, 8, 58, 8, 186, 8, 122, 8, 250, 8, 6, 8, 134, 8, 70, 8, 198, 8, 38, 8, 166, 8, 102, 8, 230, 8, 22, 8, 150, 8, 86, 8, 214, 8, 54, 8, 182, 8, 118, 8, 246, 8, 14, 8, 142, 8, 78, 8, 206, 8, 46, 8, 174, 8, 110, 8, 238, 8, 30, 8, 158, 8, 94, 8, 222, 8, 62, 8, 190, 8, 126, 8, 254, 8, 1, 8, 129, 8, 65, 8, 193, 8, 33, 8, 161, 8, 97, 8, 225, 8, 17, 8, 145, 8, 81, 8, 209, 8, 49, 8, 177, 8, 113, 8, 241, 8, 9, 8, 137, 8, 73, 8, 201, 8, 41, 8, 169, 8, 105, 8, 233, 8, 25, 8, 153, 8, 89, 8, 217, 8, 57, 8, 185, 8, 121, 8, 249, 8, 5, 8, 133, 8, 69, 8, 197, 8, 37, 8, 165, 8, 101, 8, 229, 8, 21, 8, 149, 8, 85, 8, 213, 8, 53, 8, 181, 8, 117, 8, 245, 8, 13, 8, 141, 8, 77, 8, 205, 8, 45, 8, 173, 8, 109, 8, 237, 8, 29, 8, 157, 8, 93, 8, 221, 8, 61, 8, 189, 8, 125, 8, 253, 8, 19, 9, 275, 9, 147, 9, 403, 9, 83, 9, 339, 9, 211, 9, 467, 9, 51, 9, 307, 9, 179, 9, 435, 9, 115, 9, 371, 9, 243, 9, 499, 9, 11, 9, 267, 9, 139, 9, 395, 9, 75, 9, 331, 9, 203, 9, 459, 9, 43, 9, 299, 9, 171, 9, 427, 9, 107, 9, 363, 9, 235, 9, 491, 9, 27, 9, 283, 9, 155, 9, 411, 9, 91, 9, 347, 9, 219, 9, 475, 9, 59, 9, 315, 9, 187, 9, 443, 9, 123, 9, 379, 9, 251, 9, 507, 9, 7, 9, 263, 9, 135, 9, 391, 9, 71, 9, 327, 9, 199, 9, 455, 9, 39, 9, 295, 9, 167, 9, 423, 9, 103, 9, 359, 9, 231, 9, 487, 9, 23, 9, 279, 9, 151, 9, 407, 9, 87, 9, 343, 9, 215, 9, 471, 9, 55, 9, 311, 9, 183, 9, 439, 9, 119, 9, 375, 9, 247, 9, 503, 9, 15, 9, 271, 9, 143, 9, 399, 9, 79, 9, 335, 9, 207, 9, 463, 9, 47, 9, 303, 9, 175, 9, 431, 9, 111, 9, 367, 9, 239, 9, 495, 9, 31, 9, 287, 9, 159, 9, 415, 9, 95, 9, 351, 9, 223, 9, 479, 9, 63, 9, 319, 9, 191, 9, 447, 9, 127, 9, 383, 9, 255, 9, 511, 9, 0, 7, 64, 7, 32, 7, 96, 7, 16, 7, 80, 7, 48, 7, 112, 7, 8, 7, 72, 7, 40, 7, 104, 7, 24, 7, 88, 7, 56, 7, 120, 7, 4, 7, 68, 7, 36, 7, 100, 7, 20, 7, 84, 7, 52, 7, 116, 7, 3, 8, 131, 8, 67, 8, 195, 8, 35, 8, 163, 8, 99, 8, 227, 8],
"static_dtree", [0, 5, 16, 5, 8, 5, 24, 5, 4, 5, 20, 5, 12, 5, 28, 5, 2, 5, 18, 5, 10, 5, 26, 5, 6, 5, 22, 5, 14, 5, 30, 5, 1, 5, 17, 5, 9, 5, 25, 5, 5, 5, 21, 5, 13, 5, 29, 5, 3, 5, 19, 5, 11, 5, 27, 5, 7, 5, 23, 5]);
c$.static_l_desc = c$.prototype.static_l_desc =  new JZ.StaticTree (JZ.StaticTree.static_ltree, JZ.Tree.extra_lbits, 257, 286, 15);
c$.static_d_desc = c$.prototype.static_d_desc =  new JZ.StaticTree (JZ.StaticTree.static_dtree, JZ.Tree.extra_dbits, 0, 30, 15);
c$.static_bl_desc = c$.prototype.static_bl_desc =  new JZ.StaticTree (null, JZ.Tree.extra_blbits, 0, 19, 7);
});
Clazz_declarePackage ("JZ");
Clazz_load (null, "JZ.GZIPHeader", ["JZ.ZStream", "java.lang.IllegalArgumentException", "$.InternalError"], function () {
c$ = Clazz_decorateAsClass (function () {
this.text = false;
this.fhcrc = false;
this.time = 0;
this.xflags = 0;
this.os = 255;
this.extra = null;
this.name = null;
this.comment = null;
this.hcrc = 0;
this.crc = 0;
this.done = false;
this.mtime = 0;
Clazz_instantialize (this, arguments);
}, JZ, "GZIPHeader", null, Cloneable);
$_M(c$, "setModifiedTime", 
function (mtime) {
this.mtime = mtime;
}, "~N");
$_M(c$, "getModifiedTime", 
function () {
return this.mtime;
});
$_M(c$, "setOS", 
function (os) {
if ((0 <= os && os <= 13) || os == 255) this.os = os;
 else throw  new IllegalArgumentException ("os: " + os);
}, "~N");
$_M(c$, "getOS", 
function () {
return this.os;
});
$_M(c$, "setName", 
function (name) {
this.name = JZ.ZStream.getBytes (name);
}, "~S");
$_M(c$, "getName", 
function () {
if (this.name == null) return "";
try {
return  String.instantialize (this.name, "ISO-8859-1");
} catch (e) {
if (Clazz_exceptionOf (e, java.io.UnsupportedEncodingException)) {
throw  new InternalError (e.toString ());
} else {
throw e;
}
}
});
$_M(c$, "setComment", 
function (comment) {
this.comment = JZ.ZStream.getBytes (comment);
}, "~S");
$_M(c$, "getComment", 
function () {
if (this.comment == null) return "";
try {
return  String.instantialize (this.comment, "ISO-8859-1");
} catch (e) {
if (Clazz_exceptionOf (e, java.io.UnsupportedEncodingException)) {
throw  new InternalError (e.toString ());
} else {
throw e;
}
}
});
$_M(c$, "setCRC", 
function (crc) {
this.crc = crc;
}, "~N");
$_M(c$, "getCRC", 
function () {
return this.crc;
});
$_M(c$, "put", 
function (d) {
var flag = 0;
if (this.text) {
flag |= 1;
}if (this.fhcrc) {
flag |= 2;
}if (this.extra != null) {
flag |= 4;
}if (this.name != null) {
flag |= 8;
}if (this.comment != null) {
flag |= 16;
}var xfl = 0;
if (d.level == 1) {
xfl |= 4;
} else if (d.level == 9) {
xfl |= 2;
}d.put_short (0x8b1f);
d.put_byteB (8);
d.put_byteB (flag);
d.put_byteB (this.mtime);
d.put_byteB ((this.mtime >> 8));
d.put_byteB ((this.mtime >> 16));
d.put_byteB ((this.mtime >> 24));
d.put_byteB (xfl);
d.put_byteB (this.os);
if (this.extra != null) {
d.put_byteB (this.extra.length);
d.put_byteB ((this.extra.length >> 8));
d.put_byte (this.extra, 0, this.extra.length);
}if (this.name != null) {
d.put_byte (this.name, 0, this.name.length);
d.put_byteB (0);
}if (this.comment != null) {
d.put_byte (this.comment, 0, this.comment.length);
d.put_byteB (0);
}}, "JZ.Deflate");
$_M(c$, "clone", 
function () {
var gheader = Clazz_superCall (this, JZ.GZIPHeader, "clone", []);
var tmp;
if (gheader.extra != null) {
tmp =  Clazz_newByteArray (gheader.extra.length, 0);
System.arraycopy (gheader.extra, 0, tmp, 0, tmp.length);
gheader.extra = tmp;
}if (gheader.name != null) {
tmp =  Clazz_newByteArray (gheader.name.length, 0);
System.arraycopy (gheader.name, 0, tmp, 0, tmp.length);
gheader.name = tmp;
}if (gheader.comment != null) {
tmp =  Clazz_newByteArray (gheader.comment.length, 0);
System.arraycopy (gheader.comment, 0, tmp, 0, tmp.length);
gheader.comment = tmp;
}return gheader;
});
Clazz_defineStatics (c$,
"OS_MSDOS", 0x00,
"OS_AMIGA", 0x01,
"OS_VMS", 0x02,
"OS_UNIX", 0x03,
"OS_ATARI", 0x05,
"OS_OS2", 0x06,
"OS_MACOS", 0x07,
"OS_TOPS20", 0x0a,
"OS_WIN32", 0x0b,
"OS_VMCMS", 0x04,
"OS_ZSYSTEM", 0x08,
"OS_CPM", 0x09,
"OS_QDOS", 0x0c,
"OS_RISCOS", 0x0d,
"OS_UNKNOWN", 0xff);
});
Clazz_declarePackage ("JZ");
Clazz_load (["java.lang.Exception"], "JZ.Inflate", ["JZ.Adler32", "$.CRC32", "$.GZIPHeader", "$.InfBlocks", "java.io.ByteArrayOutputStream"], function () {
c$ = Clazz_decorateAsClass (function () {
this.mode = 0;
this.method = 0;
this.was = -1;
this.need = 0;
this.marker = 0;
this.wrap = 0;
this.wbits = 0;
this.blocks = null;
this.z = null;
this.flags = 0;
this.need_bytes = -1;
this.crcbuf = null;
this.gheader = null;
if (!Clazz_isClassDefined ("JZ.Inflate.Return")) {
JZ.Inflate.$Inflate$Return$ ();
}
this.tmp_string = null;
Clazz_instantialize (this, arguments);
}, JZ, "Inflate");
Clazz_prepareFields (c$, function () {
this.crcbuf =  Clazz_newByteArray (4, 0);
});
$_M(c$, "reset", 
function () {
this.inflateReset ();
});
$_M(c$, "inflateReset", 
function () {
if (this.z == null) return -2;
this.z.total_in = this.z.total_out = 0;
this.z.msg = null;
this.mode = 14;
this.need_bytes = -1;
this.blocks.reset ();
return 0;
});
$_M(c$, "inflateEnd", 
function () {
if (this.blocks != null) {
this.blocks.free ();
}return 0;
});
Clazz_makeConstructor (c$, 
function (z) {
this.z = z;
}, "JZ.ZStream");
$_M(c$, "inflateInit", 
function (w) {
this.z.msg = null;
this.blocks = null;
this.wrap = 0;
if (w < 0) {
w = -w;
} else {
this.wrap = (w >> 4) + 1;
if (w < 48) w &= 15;
}if (w < 8 || w > 15) {
this.inflateEnd ();
return -2;
}if (this.blocks != null && this.wbits != w) {
this.blocks.free ();
this.blocks = null;
}this.wbits = w;
this.blocks =  new JZ.InfBlocks (this.z, 1 << w);
this.inflateReset ();
return 0;
}, "~N");
$_M(c$, "inflate", 
function (f) {
var r;
var b;
if (this.z == null || this.z.next_in == null) {
if (f == 4 && this.mode == 14) return 0;
return -2;
}f = f == 4 ? -5 : 0;
r = -5;
while (true) {
switch (this.mode) {
case 14:
if (this.wrap == 0) {
this.mode = 7;
break;
}try {
r = this.readBytes (2, r, f);
} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
if ((this.wrap & 2) != 0 && this.need == 0x8b1f) {
this.z.checksum =  new JZ.CRC32 ();
this.checksum (2, this.need);
if (this.gheader == null) this.gheader =  new JZ.GZIPHeader ();
this.mode = 23;
break;
}this.flags = 0;
this.method = (this.need) & 0xff;
b = ((this.need >> 8)) & 0xff;
if ((this.wrap & 1) == 0 || (((this.method << 8) + b) % 31) != 0) {
this.mode = 13;
this.z.msg = "incorrect header check";
break;
}if ((this.method & 0xf) != 8) {
this.mode = 13;
this.z.msg = "unknown compression method";
break;
}if ((this.method >> 4) + 8 > this.wbits) {
this.mode = 13;
this.z.msg = "invalid window size";
break;
}this.z.checksum =  new JZ.Adler32 ();
if ((b & 32) == 0) {
this.mode = 7;
break;
}this.mode = 2;
case 2:
if (this.z.avail_in == 0) return r;
r = f;
this.z.avail_in--;
this.z.total_in++;
this.need = ((this.z.next_in[this.z.next_in_index++] & 0xff) << 24) & 0xff000000;
this.mode = 3;
case 3:
if (this.z.avail_in == 0) return r;
r = f;
this.z.avail_in--;
this.z.total_in++;
this.need += ((this.z.next_in[this.z.next_in_index++] & 0xff) << 16) & 0xff0000;
this.mode = 4;
case 4:
if (this.z.avail_in == 0) return r;
r = f;
this.z.avail_in--;
this.z.total_in++;
this.need += ((this.z.next_in[this.z.next_in_index++] & 0xff) << 8) & 0xff00;
this.mode = 5;
case 5:
if (this.z.avail_in == 0) return r;
r = f;
this.z.avail_in--;
this.z.total_in++;
this.need += (this.z.next_in[this.z.next_in_index++] & 0xff);
this.z.checksum.resetLong (this.need);
this.mode = 6;
return 2;
case 6:
this.mode = 13;
this.z.msg = "need dictionary";
this.marker = 0;
return -2;
case 7:
r = this.blocks.proc (r);
if (r == -3) {
this.mode = 13;
this.marker = 0;
break;
}if (r == 0) {
r = f;
}if (r != 1) {
return r;
}r = f;
this.was = this.z.checksum.getValue ();
this.blocks.reset ();
if (this.wrap == 0) {
this.mode = 12;
break;
}this.mode = 8;
case 8:
if (this.z.avail_in == 0) return r;
r = f;
this.z.avail_in--;
this.z.total_in++;
this.need = ((this.z.next_in[this.z.next_in_index++] & 0xff) << 24) & 0xff000000;
this.mode = 9;
case 9:
if (this.z.avail_in == 0) return r;
r = f;
this.z.avail_in--;
this.z.total_in++;
this.need += ((this.z.next_in[this.z.next_in_index++] & 0xff) << 16) & 0xff0000;
this.mode = 10;
case 10:
if (this.z.avail_in == 0) return r;
r = f;
this.z.avail_in--;
this.z.total_in++;
this.need += ((this.z.next_in[this.z.next_in_index++] & 0xff) << 8) & 0xff00;
this.mode = 11;
case 11:
if (this.z.avail_in == 0) return r;
r = f;
this.z.avail_in--;
this.z.total_in++;
this.need += (this.z.next_in[this.z.next_in_index++] & 0xff);
if (this.flags != 0) {
this.need = ((this.need & 0xff000000) >> 24 | (this.need & 0x00ff0000) >> 8 | (this.need & 0x0000ff00) << 8 | (this.need & 0x0000ffff) << 24) & 0xffffffff;
}if (((this.was)) != ((this.need))) {
this.z.msg = "incorrect data check";
} else if (this.flags != 0 && this.gheader != null) {
this.gheader.crc = this.need;
}this.mode = 15;
case 15:
if (this.wrap != 0 && this.flags != 0) {
try {
r = this.readBytes (4, r, f);
} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
if (this.z.msg != null && this.z.msg.equals ("incorrect data check")) {
this.mode = 13;
this.marker = 5;
break;
}if (this.need != (this.z.total_out & 0xffffffff)) {
this.z.msg = "incorrect length check";
this.mode = 13;
break;
}this.z.msg = null;
} else {
if (this.z.msg != null && this.z.msg.equals ("incorrect data check")) {
this.mode = 13;
this.marker = 5;
break;
}}this.mode = 12;
case 12:
return 1;
case 13:
return -3;
case 23:
try {
r = this.readBytes (2, r, f);
} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
this.flags = (this.need) & 0xffff;
if ((this.flags & 0xff) != 8) {
this.z.msg = "unknown compression method";
this.mode = 13;
break;
}if ((this.flags & 0xe000) != 0) {
this.z.msg = "unknown header flags set";
this.mode = 13;
break;
}if ((this.flags & 0x0200) != 0) {
this.checksum (2, this.need);
}this.mode = 16;
case 16:
try {
r = this.readBytes (4, r, f);
} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
if (this.gheader != null) this.gheader.time = this.need;
if ((this.flags & 0x0200) != 0) {
this.checksum (4, this.need);
}this.mode = 17;
case 17:
try {
r = this.readBytes (2, r, f);
} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
if (this.gheader != null) {
this.gheader.xflags = (this.need) & 0xff;
this.gheader.os = ((this.need) >> 8) & 0xff;
}if ((this.flags & 0x0200) != 0) {
this.checksum (2, this.need);
}this.mode = 18;
case 18:
if ((this.flags & 0x0400) != 0) {
try {
r = this.readBytes (2, r, f);
} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
if (this.gheader != null) {
this.gheader.extra =  Clazz_newByteArray ((this.need) & 0xffff, 0);
}if ((this.flags & 0x0200) != 0) {
this.checksum (2, this.need);
}} else if (this.gheader != null) {
this.gheader.extra = null;
}this.mode = 19;
case 19:
if ((this.flags & 0x0400) != 0) {
try {
r = this.readBytes (r, f);
if (this.gheader != null) {
var foo = this.tmp_string.toByteArray ();
this.tmp_string = null;
if (foo.length == this.gheader.extra.length) {
System.arraycopy (foo, 0, this.gheader.extra, 0, foo.length);
} else {
this.z.msg = "bad extra field length";
this.mode = 13;
break;
}}} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
} else if (this.gheader != null) {
this.gheader.extra = null;
}this.mode = 20;
case 20:
if ((this.flags & 0x0800) != 0) {
try {
r = this.readString (r, f);
if (this.gheader != null) {
this.gheader.name = this.tmp_string.toByteArray ();
}this.tmp_string = null;
} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
} else if (this.gheader != null) {
this.gheader.name = null;
}this.mode = 21;
case 21:
if ((this.flags & 0x1000) != 0) {
try {
r = this.readString (r, f);
if (this.gheader != null) {
this.gheader.comment = this.tmp_string.toByteArray ();
}this.tmp_string = null;
} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
} else if (this.gheader != null) {
this.gheader.comment = null;
}this.mode = 22;
case 22:
if ((this.flags & 0x0200) != 0) {
try {
r = this.readBytes (2, r, f);
} catch (e) {
if (Clazz_exceptionOf (e, JZ.Inflate.Return)) {
return e.r;
} else {
throw e;
}
}
if (this.gheader != null) {
this.gheader.hcrc = (this.need & 0xffff);
}if (this.need != (this.z.checksum.getValue () & 0xffff)) {
this.mode = 13;
this.z.msg = "header crc mismatch";
this.marker = 5;
break;
}}this.z.checksum =  new JZ.CRC32 ();
this.mode = 7;
break;
default:
return -2;
}
}
}, "~N");
$_M(c$, "inflateSetDictionary", 
function (dictionary, dictLength) {
if (this.z == null || (this.mode != 6 && this.wrap != 0)) {
return -2;
}var index = 0;
var length = dictLength;
if (this.mode == 6) {
var adler_need = this.z.checksum.getValue ();
this.z.checksum.reset ();
this.z.checksum.update (dictionary, 0, dictLength);
if (this.z.checksum.getValue () != adler_need) {
return -3;
}}this.z.checksum.reset ();
if (length >= (1 << this.wbits)) {
length = (1 << this.wbits) - 1;
index = dictLength - length;
}this.blocks.set_dictionary (dictionary, index, length);
this.mode = 7;
return 0;
}, "~A,~N");
$_M(c$, "inflateSync", 
function () {
var n;
var p;
var m;
var r;
var w;
if (this.z == null) return -2;
if (this.mode != 13) {
this.mode = 13;
this.marker = 0;
}if ((n = this.z.avail_in) == 0) return -5;
p = this.z.next_in_index;
m = this.marker;
while (n != 0 && m < 4) {
if (this.z.next_in[p] == JZ.Inflate.mark[m]) {
m++;
} else if (this.z.next_in[p] != 0) {
m = 0;
} else {
m = 4 - m;
}p++;
n--;
}
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.z.avail_in = n;
this.marker = m;
if (m != 4) {
return -3;
}r = this.z.total_in;
w = this.z.total_out;
this.inflateReset ();
this.z.total_in = r;
this.z.total_out = w;
this.mode = 7;
return 0;
});
$_M(c$, "inflateSyncPoint", 
function () {
if (this.z == null || this.blocks == null) return -2;
return this.blocks.sync_point ();
});
$_M(c$, "readBytes", 
function (n, r, f) {
if (this.need_bytes == -1) {
this.need_bytes = n;
this.need = 0;
}while (this.need_bytes > 0) {
if (this.z.avail_in == 0) {
throw Clazz_innerTypeInstance (JZ.Inflate.Return, this, null, r);
}r = f;
this.z.avail_in--;
this.z.total_in++;
this.need = this.need | ((this.z.next_in[this.z.next_in_index++] & 0xff) << ((n - this.need_bytes) * 8));
this.need_bytes--;
}
if (n == 2) {
this.need &= 0xffff;
} else if (n == 4) {
this.need &= 0xffffffff;
}this.need_bytes = -1;
return r;
}, "~N,~N,~N");
$_M(c$, "readString", 
function (r, f) {
if (this.tmp_string == null) {
this.tmp_string =  new java.io.ByteArrayOutputStream ();
}var b = 0;
do {
if (this.z.avail_in == 0) {
throw Clazz_innerTypeInstance (JZ.Inflate.Return, this, null, r);
}r = f;
this.z.avail_in--;
this.z.total_in++;
b = this.z.next_in[this.z.next_in_index];
if (b != 0) this.tmp_string.write (this.z.next_in, this.z.next_in_index, 1);
this.z.checksum.update (this.z.next_in, this.z.next_in_index, 1);
this.z.next_in_index++;
} while (b != 0);
return r;
}, "~N,~N");
$_M(c$, "readBytes", 
function (r, f) {
if (this.tmp_string == null) {
this.tmp_string =  new java.io.ByteArrayOutputStream ();
}while (this.need > 0) {
if (this.z.avail_in == 0) {
throw Clazz_innerTypeInstance (JZ.Inflate.Return, this, null, r);
}r = f;
this.z.avail_in--;
this.z.total_in++;
this.tmp_string.write (this.z.next_in, this.z.next_in_index, 1);
this.z.checksum.update (this.z.next_in, this.z.next_in_index, 1);
this.z.next_in_index++;
this.need--;
}
return r;
}, "~N,~N");
$_M(c$, "checksum", 
function (n, v) {
for (var i = 0; i < n; i++) {
this.crcbuf[i] = (v & 0xff);
v >>= 8;
}
this.z.checksum.update (this.crcbuf, 0, n);
}, "~N,~N");
$_M(c$, "getGZIPHeader", 
function () {
return this.gheader;
});
$_M(c$, "inParsingHeader", 
function () {
switch (this.mode) {
case 14:
case 2:
case 3:
case 4:
case 5:
case 23:
case 16:
case 17:
case 18:
case 19:
case 20:
case 21:
case 22:
return true;
default:
return false;
}
});
c$.$Inflate$Return$ = function () {
Clazz_pu$h ();
c$ = Clazz_decorateAsClass (function () {
Clazz_prepareCallback (this, arguments);
this.r = 0;
Clazz_instantialize (this, arguments);
}, JZ.Inflate, "Return", Exception);
Clazz_makeConstructor (c$, 
function (a) {
Clazz_superConstructor (this, JZ.Inflate.Return, []);
this.r = a;
}, "~N");
c$ = Clazz_p0p ();
};
Clazz_defineStatics (c$,
"PRESET_DICT", 0x20,
"Z_NO_FLUSH", 0,
"Z_PARTIAL_FLUSH", 1,
"Z_SYNC_FLUSH", 2,
"Z_FULL_FLUSH", 3,
"Z_FINISH", 4,
"Z_DEFLATED", 8,
"Z_OK", 0,
"Z_STREAM_END", 1,
"Z_NEED_DICT", 2,
"Z_STREAM_ERROR", -2,
"Z_DATA_ERROR", -3,
"Z_BUF_ERROR", -5,
"DICT4", 2,
"DICT3", 3,
"DICT2", 4,
"DICT1", 5,
"DICT0", 6,
"BLOCKS", 7,
"CHECK4", 8,
"CHECK3", 9,
"CHECK2", 10,
"CHECK1", 11,
"DONE", 12,
"BAD", 13,
"HEAD", 14,
"LENGTH", 15,
"TIME", 16,
"OS", 17,
"EXLEN", 18,
"EXTRA", 19,
"NAME", 20,
"COMMENT", 21,
"HCRC", 22,
"FLAGS", 23,
"mark", [0, 0, 0xff, 0xff]);
});
Clazz_declarePackage ("JZ");
c$ = Clazz_decorateAsClass (function () {
this.hn = null;
this.v = null;
this.c = null;
this.r = null;
this.u = null;
this.x = null;
Clazz_instantialize (this, arguments);
}, JZ, "InfTree");
$_M(c$, "huft_build", 
function (b, bindex, n, s, d, e, t, m, hp, hn, v) {
var a;
var f;
var g;
var h;
var i;
var j;
var k;
var l;
var mask;
var p;
var q;
var w;
var xp;
var y;
var z;
p = 0;
i = n;
do {
this.c[b[bindex + p]]++;
p++;
i--;
} while (i != 0);
if (this.c[0] == n) {
t[0] = -1;
m[0] = 0;
return 0;
}l = m[0];
for (j = 1; j <= 15; j++) if (this.c[j] != 0) break;

k = j;
if (l < j) {
l = j;
}for (i = 15; i != 0; i--) {
if (this.c[i] != 0) break;
}
g = i;
if (l > i) {
l = i;
}m[0] = l;
for (y = 1 << j; j < i; j++, y <<= 1) {
if ((y -= this.c[j]) < 0) {
return -3;
}}
if ((y -= this.c[i]) < 0) {
return -3;
}this.c[i] += y;
this.x[1] = j = 0;
p = 1;
xp = 2;
while (--i != 0) {
this.x[xp] = (j += this.c[p]);
xp++;
p++;
}
i = 0;
p = 0;
do {
if ((j = b[bindex + p]) != 0) {
v[this.x[j]++] = i;
}p++;
} while (++i < n);
n = this.x[g];
this.x[0] = i = 0;
p = 0;
h = -1;
w = -l;
this.u[0] = 0;
q = 0;
z = 0;
for (; k <= g; k++) {
a = this.c[k];
while (a-- != 0) {
while (k > w + l) {
h++;
w += l;
z = g - w;
z = (z > l) ? l : z;
if ((f = 1 << (j = k - w)) > a + 1) {
f -= a + 1;
xp = k;
if (j < z) {
while (++j < z) {
if ((f <<= 1) <= this.c[++xp]) break;
f -= this.c[xp];
}
}}z = 1 << j;
if (hn[0] + z > 1440) {
return -3;
}this.u[h] = q = hn[0];
hn[0] += z;
if (h != 0) {
this.x[h] = i;
this.r[0] = j;
this.r[1] = l;
j = i >>> (w - l);
this.r[2] = (q - this.u[h - 1] - j);
System.arraycopy (this.r, 0, hp, (this.u[h - 1] + j) * 3, 3);
} else {
t[0] = q;
}}
this.r[1] = (k - w);
if (p >= n) {
this.r[0] = 192;
} else if (v[p] < s) {
this.r[0] = (v[p] < 256 ? 0 : 96);
this.r[2] = v[p++];
} else {
this.r[0] = (e[v[p] - s] + 16 + 64);
this.r[2] = d[v[p++] - s];
}f = 1 << (k - w);
for (j = i >>> w; j < z; j += f) {
System.arraycopy (this.r, 0, hp, (q + j) * 3, 3);
}
for (j = 1 << (k - 1); (i & j) != 0; j >>>= 1) {
i ^= j;
}
i ^= j;
mask = (1 << w) - 1;
while ((i & mask) != this.x[h]) {
h--;
w -= l;
mask = (1 << w) - 1;
}
}
}
return y != 0 && g != 1 ? -5 : 0;
}, "~A,~N,~N,~N,~A,~A,~A,~A,~A,~A,~A");
$_M(c$, "inflate_trees_bits", 
function (c, bb, tb, hp, z) {
var result;
this.initWorkArea (19);
this.hn[0] = 0;
result = this.huft_build (c, 0, 19, 19, null, null, tb, bb, hp, this.hn, this.v);
if (result == -3) {
z.msg = "oversubscribed dynamic bit lengths tree";
} else if (result == -5 || bb[0] == 0) {
z.msg = "incomplete dynamic bit lengths tree";
result = -3;
}return result;
}, "~A,~A,~A,~A,JZ.ZStream");
$_M(c$, "inflate_trees_dynamic", 
function (nl, nd, c, bl, bd, tl, td, hp, z) {
var result;
this.initWorkArea (288);
this.hn[0] = 0;
result = this.huft_build (c, 0, nl, 257, JZ.InfTree.cplens, JZ.InfTree.cplext, tl, bl, hp, this.hn, this.v);
if (result != 0 || bl[0] == 0) {
if (result == -3) {
z.msg = "oversubscribed literal/length tree";
} else if (result != -4) {
z.msg = "incomplete literal/length tree";
result = -3;
}return result;
}this.initWorkArea (288);
result = this.huft_build (c, nl, nd, 0, JZ.InfTree.cpdist, JZ.InfTree.cpdext, td, bd, hp, this.hn, this.v);
if (result != 0 || (bd[0] == 0 && nl > 257)) {
if (result == -3) {
z.msg = "oversubscribed distance tree";
} else if (result == -5) {
z.msg = "incomplete distance tree";
result = -3;
} else if (result != -4) {
z.msg = "empty distance tree with lengths";
result = -3;
}return result;
}return 0;
}, "~N,~N,~A,~A,~A,~A,~A,~A,JZ.ZStream");
c$.inflate_trees_fixed = $_M(c$, "inflate_trees_fixed", 
function (bl, bd, tl, td, z) {
bl[0] = 9;
bd[0] = 5;
tl[0] = JZ.InfTree.fixed_tl;
td[0] = JZ.InfTree.fixed_td;
return 0;
}, "~A,~A,~A,~A,JZ.ZStream");
$_M(c$, "initWorkArea", 
function (vsize) {
if (this.hn == null) {
this.hn =  Clazz_newIntArray (1, 0);
this.v =  Clazz_newIntArray (vsize, 0);
this.c =  Clazz_newIntArray (16, 0);
this.r =  Clazz_newIntArray (3, 0);
this.u =  Clazz_newIntArray (15, 0);
this.x =  Clazz_newIntArray (16, 0);
}if (this.v.length < vsize) {
this.v =  Clazz_newIntArray (vsize, 0);
}for (var i = 0; i < vsize; i++) {
this.v[i] = 0;
}
for (var i = 0; i < 16; i++) {
this.c[i] = 0;
}
for (var i = 0; i < 3; i++) {
this.r[i] = 0;
}
System.arraycopy (this.c, 0, this.u, 0, 15);
System.arraycopy (this.c, 0, this.x, 0, 16);
}, "~N");
Clazz_defineStatics (c$,
"MANY", 1440,
"Z_OK", 0,
"Z_DATA_ERROR", -3,
"Z_MEM_ERROR", -4,
"Z_BUF_ERROR", -5,
"fixed_bl", 9,
"fixed_bd", 5,
"fixed_tl", [96, 7, 256, 0, 8, 80, 0, 8, 16, 84, 8, 115, 82, 7, 31, 0, 8, 112, 0, 8, 48, 0, 9, 192, 80, 7, 10, 0, 8, 96, 0, 8, 32, 0, 9, 160, 0, 8, 0, 0, 8, 128, 0, 8, 64, 0, 9, 224, 80, 7, 6, 0, 8, 88, 0, 8, 24, 0, 9, 144, 83, 7, 59, 0, 8, 120, 0, 8, 56, 0, 9, 208, 81, 7, 17, 0, 8, 104, 0, 8, 40, 0, 9, 176, 0, 8, 8, 0, 8, 136, 0, 8, 72, 0, 9, 240, 80, 7, 4, 0, 8, 84, 0, 8, 20, 85, 8, 227, 83, 7, 43, 0, 8, 116, 0, 8, 52, 0, 9, 200, 81, 7, 13, 0, 8, 100, 0, 8, 36, 0, 9, 168, 0, 8, 4, 0, 8, 132, 0, 8, 68, 0, 9, 232, 80, 7, 8, 0, 8, 92, 0, 8, 28, 0, 9, 152, 84, 7, 83, 0, 8, 124, 0, 8, 60, 0, 9, 216, 82, 7, 23, 0, 8, 108, 0, 8, 44, 0, 9, 184, 0, 8, 12, 0, 8, 140, 0, 8, 76, 0, 9, 248, 80, 7, 3, 0, 8, 82, 0, 8, 18, 85, 8, 163, 83, 7, 35, 0, 8, 114, 0, 8, 50, 0, 9, 196, 81, 7, 11, 0, 8, 98, 0, 8, 34, 0, 9, 164, 0, 8, 2, 0, 8, 130, 0, 8, 66, 0, 9, 228, 80, 7, 7, 0, 8, 90, 0, 8, 26, 0, 9, 148, 84, 7, 67, 0, 8, 122, 0, 8, 58, 0, 9, 212, 82, 7, 19, 0, 8, 106, 0, 8, 42, 0, 9, 180, 0, 8, 10, 0, 8, 138, 0, 8, 74, 0, 9, 244, 80, 7, 5, 0, 8, 86, 0, 8, 22, 192, 8, 0, 83, 7, 51, 0, 8, 118, 0, 8, 54, 0, 9, 204, 81, 7, 15, 0, 8, 102, 0, 8, 38, 0, 9, 172, 0, 8, 6, 0, 8, 134, 0, 8, 70, 0, 9, 236, 80, 7, 9, 0, 8, 94, 0, 8, 30, 0, 9, 156, 84, 7, 99, 0, 8, 126, 0, 8, 62, 0, 9, 220, 82, 7, 27, 0, 8, 110, 0, 8, 46, 0, 9, 188, 0, 8, 14, 0, 8, 142, 0, 8, 78, 0, 9, 252, 96, 7, 256, 0, 8, 81, 0, 8, 17, 85, 8, 131, 82, 7, 31, 0, 8, 113, 0, 8, 49, 0, 9, 194, 80, 7, 10, 0, 8, 97, 0, 8, 33, 0, 9, 162, 0, 8, 1, 0, 8, 129, 0, 8, 65, 0, 9, 226, 80, 7, 6, 0, 8, 89, 0, 8, 25, 0, 9, 146, 83, 7, 59, 0, 8, 121, 0, 8, 57, 0, 9, 210, 81, 7, 17, 0, 8, 105, 0, 8, 41, 0, 9, 178, 0, 8, 9, 0, 8, 137, 0, 8, 73, 0, 9, 242, 80, 7, 4, 0, 8, 85, 0, 8, 21, 80, 8, 258, 83, 7, 43, 0, 8, 117, 0, 8, 53, 0, 9, 202, 81, 7, 13, 0, 8, 101, 0, 8, 37, 0, 9, 170, 0, 8, 5, 0, 8, 133, 0, 8, 69, 0, 9, 234, 80, 7, 8, 0, 8, 93, 0, 8, 29, 0, 9, 154, 84, 7, 83, 0, 8, 125, 0, 8, 61, 0, 9, 218, 82, 7, 23, 0, 8, 109, 0, 8, 45, 0, 9, 186, 0, 8, 13, 0, 8, 141, 0, 8, 77, 0, 9, 250, 80, 7, 3, 0, 8, 83, 0, 8, 19, 85, 8, 195, 83, 7, 35, 0, 8, 115, 0, 8, 51, 0, 9, 198, 81, 7, 11, 0, 8, 99, 0, 8, 35, 0, 9, 166, 0, 8, 3, 0, 8, 131, 0, 8, 67, 0, 9, 230, 80, 7, 7, 0, 8, 91, 0, 8, 27, 0, 9, 150, 84, 7, 67, 0, 8, 123, 0, 8, 59, 0, 9, 214, 82, 7, 19, 0, 8, 107, 0, 8, 43, 0, 9, 182, 0, 8, 11, 0, 8, 139, 0, 8, 75, 0, 9, 246, 80, 7, 5, 0, 8, 87, 0, 8, 23, 192, 8, 0, 83, 7, 51, 0, 8, 119, 0, 8, 55, 0, 9, 206, 81, 7, 15, 0, 8, 103, 0, 8, 39, 0, 9, 174, 0, 8, 7, 0, 8, 135, 0, 8, 71, 0, 9, 238, 80, 7, 9, 0, 8, 95, 0, 8, 31, 0, 9, 158, 84, 7, 99, 0, 8, 127, 0, 8, 63, 0, 9, 222, 82, 7, 27, 0, 8, 111, 0, 8, 47, 0, 9, 190, 0, 8, 15, 0, 8, 143, 0, 8, 79, 0, 9, 254, 96, 7, 256, 0, 8, 80, 0, 8, 16, 84, 8, 115, 82, 7, 31, 0, 8, 112, 0, 8, 48, 0, 9, 193, 80, 7, 10, 0, 8, 96, 0, 8, 32, 0, 9, 161, 0, 8, 0, 0, 8, 128, 0, 8, 64, 0, 9, 225, 80, 7, 6, 0, 8, 88, 0, 8, 24, 0, 9, 145, 83, 7, 59, 0, 8, 120, 0, 8, 56, 0, 9, 209, 81, 7, 17, 0, 8, 104, 0, 8, 40, 0, 9, 177, 0, 8, 8, 0, 8, 136, 0, 8, 72, 0, 9, 241, 80, 7, 4, 0, 8, 84, 0, 8, 20, 85, 8, 227, 83, 7, 43, 0, 8, 116, 0, 8, 52, 0, 9, 201, 81, 7, 13, 0, 8, 100, 0, 8, 36, 0, 9, 169, 0, 8, 4, 0, 8, 132, 0, 8, 68, 0, 9, 233, 80, 7, 8, 0, 8, 92, 0, 8, 28, 0, 9, 153, 84, 7, 83, 0, 8, 124, 0, 8, 60, 0, 9, 217, 82, 7, 23, 0, 8, 108, 0, 8, 44, 0, 9, 185, 0, 8, 12, 0, 8, 140, 0, 8, 76, 0, 9, 249, 80, 7, 3, 0, 8, 82, 0, 8, 18, 85, 8, 163, 83, 7, 35, 0, 8, 114, 0, 8, 50, 0, 9, 197, 81, 7, 11, 0, 8, 98, 0, 8, 34, 0, 9, 165, 0, 8, 2, 0, 8, 130, 0, 8, 66, 0, 9, 229, 80, 7, 7, 0, 8, 90, 0, 8, 26, 0, 9, 149, 84, 7, 67, 0, 8, 122, 0, 8, 58, 0, 9, 213, 82, 7, 19, 0, 8, 106, 0, 8, 42, 0, 9, 181, 0, 8, 10, 0, 8, 138, 0, 8, 74, 0, 9, 245, 80, 7, 5, 0, 8, 86, 0, 8, 22, 192, 8, 0, 83, 7, 51, 0, 8, 118, 0, 8, 54, 0, 9, 205, 81, 7, 15, 0, 8, 102, 0, 8, 38, 0, 9, 173, 0, 8, 6, 0, 8, 134, 0, 8, 70, 0, 9, 237, 80, 7, 9, 0, 8, 94, 0, 8, 30, 0, 9, 157, 84, 7, 99, 0, 8, 126, 0, 8, 62, 0, 9, 221, 82, 7, 27, 0, 8, 110, 0, 8, 46, 0, 9, 189, 0, 8, 14, 0, 8, 142, 0, 8, 78, 0, 9, 253, 96, 7, 256, 0, 8, 81, 0, 8, 17, 85, 8, 131, 82, 7, 31, 0, 8, 113, 0, 8, 49, 0, 9, 195, 80, 7, 10, 0, 8, 97, 0, 8, 33, 0, 9, 163, 0, 8, 1, 0, 8, 129, 0, 8, 65, 0, 9, 227, 80, 7, 6, 0, 8, 89, 0, 8, 25, 0, 9, 147, 83, 7, 59, 0, 8, 121, 0, 8, 57, 0, 9, 211, 81, 7, 17, 0, 8, 105, 0, 8, 41, 0, 9, 179, 0, 8, 9, 0, 8, 137, 0, 8, 73, 0, 9, 243, 80, 7, 4, 0, 8, 85, 0, 8, 21, 80, 8, 258, 83, 7, 43, 0, 8, 117, 0, 8, 53, 0, 9, 203, 81, 7, 13, 0, 8, 101, 0, 8, 37, 0, 9, 171, 0, 8, 5, 0, 8, 133, 0, 8, 69, 0, 9, 235, 80, 7, 8, 0, 8, 93, 0, 8, 29, 0, 9, 155, 84, 7, 83, 0, 8, 125, 0, 8, 61, 0, 9, 219, 82, 7, 23, 0, 8, 109, 0, 8, 45, 0, 9, 187, 0, 8, 13, 0, 8, 141, 0, 8, 77, 0, 9, 251, 80, 7, 3, 0, 8, 83, 0, 8, 19, 85, 8, 195, 83, 7, 35, 0, 8, 115, 0, 8, 51, 0, 9, 199, 81, 7, 11, 0, 8, 99, 0, 8, 35, 0, 9, 167, 0, 8, 3, 0, 8, 131, 0, 8, 67, 0, 9, 231, 80, 7, 7, 0, 8, 91, 0, 8, 27, 0, 9, 151, 84, 7, 67, 0, 8, 123, 0, 8, 59, 0, 9, 215, 82, 7, 19, 0, 8, 107, 0, 8, 43, 0, 9, 183, 0, 8, 11, 0, 8, 139, 0, 8, 75, 0, 9, 247, 80, 7, 5, 0, 8, 87, 0, 8, 23, 192, 8, 0, 83, 7, 51, 0, 8, 119, 0, 8, 55, 0, 9, 207, 81, 7, 15, 0, 8, 103, 0, 8, 39, 0, 9, 175, 0, 8, 7, 0, 8, 135, 0, 8, 71, 0, 9, 239, 80, 7, 9, 0, 8, 95, 0, 8, 31, 0, 9, 159, 84, 7, 99, 0, 8, 127, 0, 8, 63, 0, 9, 223, 82, 7, 27, 0, 8, 111, 0, 8, 47, 0, 9, 191, 0, 8, 15, 0, 8, 143, 0, 8, 79, 0, 9, 255],
"fixed_td", [80, 5, 1, 87, 5, 257, 83, 5, 17, 91, 5, 4097, 81, 5, 5, 89, 5, 1025, 85, 5, 65, 93, 5, 16385, 80, 5, 3, 88, 5, 513, 84, 5, 33, 92, 5, 8193, 82, 5, 9, 90, 5, 2049, 86, 5, 129, 192, 5, 24577, 80, 5, 2, 87, 5, 385, 83, 5, 25, 91, 5, 6145, 81, 5, 7, 89, 5, 1537, 85, 5, 97, 93, 5, 24577, 80, 5, 4, 88, 5, 769, 84, 5, 49, 92, 5, 12289, 82, 5, 13, 90, 5, 3073, 86, 5, 193, 192, 5, 24577],
"cplens", [3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 15, 17, 19, 23, 27, 31, 35, 43, 51, 59, 67, 83, 99, 115, 131, 163, 195, 227, 258, 0, 0],
"cplext", [0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 0, 112, 112],
"cpdist", [1, 2, 3, 4, 5, 7, 9, 13, 17, 25, 33, 49, 65, 97, 129, 193, 257, 385, 513, 769, 1025, 1537, 2049, 3073, 4097, 6145, 8193, 12289, 16385, 24577],
"cpdext", [0, 0, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 6, 7, 7, 8, 8, 9, 9, 10, 10, 11, 11, 12, 12, 13, 13],
"BMAX", 15);
Clazz_declarePackage ("JZ");
Clazz_load (["JZ.InfTree"], "JZ.InfBlocks", ["JZ.InfCodes"], function () {
c$ = Clazz_decorateAsClass (function () {
this.mode = 0;
this.left = 0;
this.table = 0;
this.index = 0;
this.blens = null;
this.bb = null;
this.tb = null;
this.bl = null;
this.bd = null;
this.tl = null;
this.td = null;
this.tli = null;
this.tdi = null;
this.codes = null;
this.last = 0;
this.bitk = 0;
this.bitb = 0;
this.hufts = null;
this.window = null;
this.end = 0;
this.read = 0;
this.write = 0;
this.check = false;
this.inftree = null;
this.z = null;
Clazz_instantialize (this, arguments);
}, JZ, "InfBlocks");
Clazz_prepareFields (c$, function () {
this.bb =  Clazz_newIntArray (1, 0);
this.tb =  Clazz_newIntArray (1, 0);
this.bl =  Clazz_newIntArray (1, 0);
this.bd =  Clazz_newIntArray (1, 0);
this.tli =  Clazz_newIntArray (1, 0);
this.tdi =  Clazz_newIntArray (1, 0);
this.inftree =  new JZ.InfTree ();
});
Clazz_makeConstructor (c$, 
function (z, w) {
this.z = z;
this.codes =  new JZ.InfCodes (this.z, this);
this.hufts =  Clazz_newIntArray (4320, 0);
this.window =  Clazz_newByteArray (w, 0);
this.end = w;
this.check = (z.istate.wrap == 0) ? false : true;
this.mode = 0;
{
this.tl = Clazz_newArray(1, null);
this.td = Clazz_newArray(1, null);
}this.reset ();
}, "JZ.ZStream,~N");
$_M(c$, "reset", 
function () {
if (this.mode == 6) {
this.codes.free (this.z);
}this.mode = 0;
this.bitk = 0;
this.bitb = 0;
this.read = this.write = 0;
if (this.check) {
this.z.checksum.reset ();
}});
$_M(c$, "proc", 
function (r) {
var t;
var b;
var k;
var p;
var n;
var q;
var m;
{
p = this.z.next_in_index;
n = this.z.avail_in;
b = this.bitb;
k = this.bitk;
}{
q = this.write;
m = (q < this.read ? this.read - q - 1 : this.end - q);
}while (true) {
switch (this.mode) {
case 0:
while (k < (3)) {
if (n != 0) {
r = 0;
} else {
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
t = (b & 7);
this.last = t & 1;
switch (t >>> 1) {
case 0:
{
b >>>= (3);
k -= (3);
}t = k & 7;
{
b >>>= (t);
k -= (t);
}this.mode = 1;
break;
case 1:
JZ.InfTree.inflate_trees_fixed (this.bl, this.bd, this.tl, this.td, this.z);
this.codes.init (this.bl[0], this.bd[0], this.tl[0], 0, this.td[0], 0);
{
b >>>= (3);
k -= (3);
}this.mode = 6;
break;
case 2:
{
b >>>= (3);
k -= (3);
}this.mode = 3;
break;
case 3:
{
b >>>= (3);
k -= (3);
}this.mode = 9;
this.z.msg = "invalid block type";
r = -3;
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}
break;
case 1:
while (k < (32)) {
if (n != 0) {
r = 0;
} else {
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
if ((((~b) >>> 16) & 0xffff) != (b & 0xffff)) {
this.mode = 9;
this.z.msg = "invalid stored block lengths";
r = -3;
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}this.left = (b & 0xffff);
b = k = 0;
this.mode = this.left != 0 ? 2 : (this.last != 0 ? 7 : 0);
break;
case 2:
if (n == 0) {
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}if (m == 0) {
if (q == this.end && this.read != 0) {
q = 0;
m = (q < this.read ? this.read - q - 1 : this.end - q);
}if (m == 0) {
this.write = q;
r = this.inflate_flush (r);
q = this.write;
m = (q < this.read ? this.read - q - 1 : this.end - q);
if (q == this.end && this.read != 0) {
q = 0;
m = (q < this.read ? this.read - q - 1 : this.end - q);
}if (m == 0) {
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}}}r = 0;
t = this.left;
if (t > n) t = n;
if (t > m) t = m;
System.arraycopy (this.z.next_in, p, this.window, q, t);
p += t;
n -= t;
q += t;
m -= t;
if ((this.left -= t) != 0) break;
this.mode = this.last != 0 ? 7 : 0;
break;
case 3:
while (k < (14)) {
if (n != 0) {
r = 0;
} else {
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
this.table = t = (b & 0x3fff);
if ((t & 0x1f) > 29 || ((t >> 5) & 0x1f) > 29) {
this.mode = 9;
this.z.msg = "too many length or distance symbols";
r = -3;
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}t = 258 + (t & 0x1f) + ((t >> 5) & 0x1f);
if (this.blens == null || this.blens.length < t) {
this.blens =  Clazz_newIntArray (t, 0);
} else {
for (var i = 0; i < t; i++) {
this.blens[i] = 0;
}
}{
b >>>= (14);
k -= (14);
}this.index = 0;
this.mode = 4;
case 4:
while (this.index < 4 + (this.table >>> 10)) {
while (k < (3)) {
if (n != 0) {
r = 0;
} else {
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
this.blens[JZ.InfBlocks.border[this.index++]] = b & 7;
{
b >>>= (3);
k -= (3);
}}
while (this.index < 19) {
this.blens[JZ.InfBlocks.border[this.index++]] = 0;
}
this.bb[0] = 7;
t = this.inftree.inflate_trees_bits (this.blens, this.bb, this.tb, this.hufts, this.z);
if (t != 0) {
r = t;
if (r == -3) {
this.blens = null;
this.mode = 9;
}this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}this.index = 0;
this.mode = 5;
case 5:
while (true) {
t = this.table;
if (!(this.index < 258 + (t & 0x1f) + ((t >> 5) & 0x1f))) {
break;
}var i;
var j;
var c;
t = this.bb[0];
while (k < (t)) {
if (n != 0) {
r = 0;
} else {
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
t = this.hufts[(this.tb[0] + (b & JZ.InfBlocks.inflate_mask[t])) * 3 + 1];
c = this.hufts[(this.tb[0] + (b & JZ.InfBlocks.inflate_mask[t])) * 3 + 2];
if (c < 16) {
b >>>= (t);
k -= (t);
this.blens[this.index++] = c;
} else {
i = c == 18 ? 7 : c - 14;
j = c == 18 ? 11 : 3;
while (k < (t + i)) {
if (n != 0) {
r = 0;
} else {
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
b >>>= (t);
k -= (t);
j += (b & JZ.InfBlocks.inflate_mask[i]);
b >>>= (i);
k -= (i);
i = this.index;
t = this.table;
if (i + j > 258 + (t & 0x1f) + ((t >> 5) & 0x1f) || (c == 16 && i < 1)) {
this.blens = null;
this.mode = 9;
this.z.msg = "invalid bit length repeat";
r = -3;
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}c = c == 16 ? this.blens[i - 1] : 0;
do {
this.blens[i++] = c;
} while (--j != 0);
this.index = i;
}}
this.tb[0] = -1;
{
this.bl[0] = 9;
this.bd[0] = 6;
t = this.table;
t = this.inftree.inflate_trees_dynamic (257 + (t & 0x1f), 1 + ((t >> 5) & 0x1f), this.blens, this.bl, this.bd, this.tli, this.tdi, this.hufts, this.z);
if (t != 0) {
if (t == -3) {
this.blens = null;
this.mode = 9;
}r = t;
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}this.codes.init (this.bl[0], this.bd[0], this.hufts, this.tli[0], this.hufts, this.tdi[0]);
}this.mode = 6;
case 6:
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
if ((r = this.codes.proc (r)) != 1) {
return this.inflate_flush (r);
}r = 0;
this.codes.free (this.z);
p = this.z.next_in_index;
n = this.z.avail_in;
b = this.bitb;
k = this.bitk;
q = this.write;
m = (q < this.read ? this.read - q - 1 : this.end - q);
if (this.last == 0) {
this.mode = 0;
break;
}this.mode = 7;
case 7:
this.write = q;
r = this.inflate_flush (r);
q = this.write;
m = (q < this.read ? this.read - q - 1 : this.end - q);
if (this.read != this.write) {
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}this.mode = 8;
case 8:
r = 1;
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
case 9:
r = -3;
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
default:
r = -2;
this.bitb = b;
this.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.write = q;
return this.inflate_flush (r);
}
}
}, "~N");
$_M(c$, "free", 
function () {
this.reset ();
this.window = null;
this.hufts = null;
});
$_M(c$, "set_dictionary", 
function (d, start, n) {
System.arraycopy (d, start, this.window, 0, n);
this.read = this.write = n;
}, "~A,~N,~N");
$_M(c$, "sync_point", 
function () {
return this.mode == 1 ? 1 : 0;
});
$_M(c$, "inflate_flush", 
function (r) {
var n;
var p;
var q;
p = this.z.next_out_index;
q = this.read;
n = ((q <= this.write ? this.write : this.end) - q);
if (n > this.z.avail_out) n = this.z.avail_out;
if (n != 0 && r == -5) r = 0;
this.z.avail_out -= n;
this.z.total_out += n;
if (this.check && n > 0) {
this.z.checksum.update (this.window, q, n);
}System.arraycopy (this.window, q, this.z.next_out, p, n);
p += n;
q += n;
if (q == this.end) {
q = 0;
if (this.write == this.end) this.write = 0;
n = this.write - q;
if (n > this.z.avail_out) n = this.z.avail_out;
if (n != 0 && r == -5) r = 0;
this.z.avail_out -= n;
this.z.total_out += n;
if (this.check && n > 0) {
this.z.checksum.update (this.window, q, n);
}System.arraycopy (this.window, q, this.z.next_out, p, n);
p += n;
q += n;
}this.z.next_out_index = p;
this.read = q;
return r;
}, "~N");
Clazz_defineStatics (c$,
"MANY", 1440,
"inflate_mask", [0x00000000, 0x00000001, 0x00000003, 0x00000007, 0x0000000f, 0x0000001f, 0x0000003f, 0x0000007f, 0x000000ff, 0x000001ff, 0x000003ff, 0x000007ff, 0x00000fff, 0x00001fff, 0x00003fff, 0x00007fff, 0x0000ffff],
"border", [16, 17, 18, 0, 8, 7, 9, 6, 10, 5, 11, 4, 12, 3, 13, 2, 14, 1, 15],
"Z_OK", 0,
"Z_STREAM_END", 1,
"Z_STREAM_ERROR", -2,
"Z_DATA_ERROR", -3,
"Z_BUF_ERROR", -5,
"TYPE", 0,
"LENS", 1,
"STORED", 2,
"TABLE", 3,
"BTREE", 4,
"DTREE", 5,
"CODES", 6,
"DRY", 7,
"DONE", 8,
"BAD", 9);
});
Clazz_declarePackage ("JZ");
c$ = Clazz_decorateAsClass (function () {
this.mode = 0;
this.len = 0;
this.tree = null;
this.tree_index = 0;
this.need = 0;
this.lit = 0;
this.get = 0;
this.dist = 0;
this.lbits = 0;
this.dbits = 0;
this.ltree = null;
this.ltree_index = 0;
this.dtree = null;
this.dtree_index = 0;
this.z = null;
this.s = null;
Clazz_instantialize (this, arguments);
}, JZ, "InfCodes");
Clazz_makeConstructor (c$, 
function (z, s) {
this.z = z;
this.s = s;
}, "JZ.ZStream,JZ.InfBlocks");
$_M(c$, "init", 
function (bl, bd, tl, tl_index, td, td_index) {
this.mode = 0;
this.lbits = bl;
this.dbits = bd;
this.ltree = tl;
this.ltree_index = tl_index;
this.dtree = td;
this.dtree_index = td_index;
this.tree = null;
}, "~N,~N,~A,~N,~A,~N");
$_M(c$, "proc", 
function (r) {
var j;
var tindex;
var e;
var b = 0;
var k = 0;
var p = 0;
var n;
var q;
var m;
var f;
p = this.z.next_in_index;
n = this.z.avail_in;
b = this.s.bitb;
k = this.s.bitk;
q = this.s.write;
m = q < this.s.read ? this.s.read - q - 1 : this.s.end - q;
while (true) {
switch (this.mode) {
case 0:
if (m >= 258 && n >= 10) {
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
r = this.inflate_fast (this.lbits, this.dbits, this.ltree, this.ltree_index, this.dtree, this.dtree_index, this.s, this.z);
p = this.z.next_in_index;
n = this.z.avail_in;
b = this.s.bitb;
k = this.s.bitk;
q = this.s.write;
m = q < this.s.read ? this.s.read - q - 1 : this.s.end - q;
if (r != 0) {
this.mode = r == 1 ? 7 : 9;
break;
}}this.need = this.lbits;
this.tree = this.ltree;
this.tree_index = this.ltree_index;
this.mode = 1;
case 1:
j = this.need;
while (k < (j)) {
if (n != 0) r = 0;
 else {
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
tindex = (this.tree_index + (b & JZ.InfCodes.inflate_mask[j])) * 3;
b >>>= (this.tree[tindex + 1]);
k -= (this.tree[tindex + 1]);
e = this.tree[tindex];
if (e == 0) {
this.lit = this.tree[tindex + 2];
this.mode = 6;
break;
}if ((e & 16) != 0) {
this.get = e & 15;
this.len = this.tree[tindex + 2];
this.mode = 2;
break;
}if ((e & 64) == 0) {
this.need = e;
this.tree_index = Clazz_doubleToInt (tindex / 3) + this.tree[tindex + 2];
break;
}if ((e & 32) != 0) {
this.mode = 7;
break;
}this.mode = 9;
this.z.msg = "invalid literal/length code";
r = -3;
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
case 2:
j = this.get;
while (k < (j)) {
if (n != 0) r = 0;
 else {
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
this.len += (b & JZ.InfCodes.inflate_mask[j]);
b >>= j;
k -= j;
this.need = this.dbits;
this.tree = this.dtree;
this.tree_index = this.dtree_index;
this.mode = 3;
case 3:
j = this.need;
while (k < (j)) {
if (n != 0) r = 0;
 else {
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
tindex = (this.tree_index + (b & JZ.InfCodes.inflate_mask[j])) * 3;
b >>= this.tree[tindex + 1];
k -= this.tree[tindex + 1];
e = (this.tree[tindex]);
if ((e & 16) != 0) {
this.get = e & 15;
this.dist = this.tree[tindex + 2];
this.mode = 4;
break;
}if ((e & 64) == 0) {
this.need = e;
this.tree_index = Clazz_doubleToInt (tindex / 3) + this.tree[tindex + 2];
break;
}this.mode = 9;
this.z.msg = "invalid distance code";
r = -3;
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
case 4:
j = this.get;
while (k < (j)) {
if (n != 0) r = 0;
 else {
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
}n--;
b |= (this.z.next_in[p++] & 0xff) << k;
k += 8;
}
this.dist += (b & JZ.InfCodes.inflate_mask[j]);
b >>= j;
k -= j;
this.mode = 5;
case 5:
f = q - this.dist;
while (f < 0) {
f += this.s.end;
}
while (this.len != 0) {
if (m == 0) {
if (q == this.s.end && this.s.read != 0) {
q = 0;
m = q < this.s.read ? this.s.read - q - 1 : this.s.end - q;
}if (m == 0) {
this.s.write = q;
r = this.s.inflate_flush (r);
q = this.s.write;
m = q < this.s.read ? this.s.read - q - 1 : this.s.end - q;
if (q == this.s.end && this.s.read != 0) {
q = 0;
m = q < this.s.read ? this.s.read - q - 1 : this.s.end - q;
}if (m == 0) {
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
}}}this.s.window[q++] = this.s.window[f++];
m--;
if (f == this.s.end) f = 0;
this.len--;
}
this.mode = 0;
break;
case 6:
if (m == 0) {
if (q == this.s.end && this.s.read != 0) {
q = 0;
m = q < this.s.read ? this.s.read - q - 1 : this.s.end - q;
}if (m == 0) {
this.s.write = q;
r = this.s.inflate_flush (r);
q = this.s.write;
m = q < this.s.read ? this.s.read - q - 1 : this.s.end - q;
if (q == this.s.end && this.s.read != 0) {
q = 0;
m = q < this.s.read ? this.s.read - q - 1 : this.s.end - q;
}if (m == 0) {
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
}}}r = 0;
this.s.window[q++] = this.lit;
m--;
this.mode = 0;
break;
case 7:
if (k > 7) {
k -= 8;
n++;
p--;
}this.s.write = q;
r = this.s.inflate_flush (r);
q = this.s.write;
m = q < this.s.read ? this.s.read - q - 1 : this.s.end - q;
if (this.s.read != this.s.write) {
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
}this.mode = 8;
case 8:
r = 1;
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
case 9:
r = -3;
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
default:
r = -2;
this.s.bitb = b;
this.s.bitk = k;
this.z.avail_in = n;
this.z.total_in += p - this.z.next_in_index;
this.z.next_in_index = p;
this.s.write = q;
return this.s.inflate_flush (r);
}
}
}, "~N");
$_M(c$, "free", 
function (z) {
}, "JZ.ZStream");
$_M(c$, "inflate_fast", 
function (bl, bd, tl, tl_index, td, td_index, s, z) {
var t;
var tp;
var tp_index;
var e;
var b;
var k;
var p;
var n;
var q;
var m;
var ml;
var md;
var c;
var d;
var r;
var tp_index_t_3;
p = z.next_in_index;
n = z.avail_in;
b = s.bitb;
k = s.bitk;
q = s.write;
m = q < s.read ? s.read - q - 1 : s.end - q;
ml = JZ.InfCodes.inflate_mask[bl];
md = JZ.InfCodes.inflate_mask[bd];
do {
while (k < (20)) {
n--;
b |= (z.next_in[p++] & 0xff) << k;
k += 8;
}
t = b & ml;
tp = tl;
tp_index = tl_index;
tp_index_t_3 = (tp_index + t) * 3;
if ((e = tp[tp_index_t_3]) == 0) {
b >>= (tp[tp_index_t_3 + 1]);
k -= (tp[tp_index_t_3 + 1]);
s.window[q++] = tp[tp_index_t_3 + 2];
m--;
continue;
}do {
b >>= (tp[tp_index_t_3 + 1]);
k -= (tp[tp_index_t_3 + 1]);
if ((e & 16) != 0) {
e &= 15;
c = tp[tp_index_t_3 + 2] + (b & JZ.InfCodes.inflate_mask[e]);
b >>= e;
k -= e;
while (k < (15)) {
n--;
b |= (z.next_in[p++] & 0xff) << k;
k += 8;
}
t = b & md;
tp = td;
tp_index = td_index;
tp_index_t_3 = (tp_index + t) * 3;
e = tp[tp_index_t_3];
do {
b >>= (tp[tp_index_t_3 + 1]);
k -= (tp[tp_index_t_3 + 1]);
if ((e & 16) != 0) {
e &= 15;
while (k < (e)) {
n--;
b |= (z.next_in[p++] & 0xff) << k;
k += 8;
}
d = tp[tp_index_t_3 + 2] + (b & JZ.InfCodes.inflate_mask[e]);
b >>= (e);
k -= (e);
m -= c;
if (q >= d) {
r = q - d;
if (q - r > 0 && 2 > (q - r)) {
s.window[q++] = s.window[r++];
s.window[q++] = s.window[r++];
c -= 2;
} else {
System.arraycopy (s.window, r, s.window, q, 2);
q += 2;
r += 2;
c -= 2;
}} else {
r = q - d;
do {
r += s.end;
} while (r < 0);
e = s.end - r;
if (c > e) {
c -= e;
if (q - r > 0 && e > (q - r)) {
do {
s.window[q++] = s.window[r++];
} while (--e != 0);
} else {
System.arraycopy (s.window, r, s.window, q, e);
q += e;
r += e;
e = 0;
}r = 0;
}}if (q - r > 0 && c > (q - r)) {
do {
s.window[q++] = s.window[r++];
} while (--c != 0);
} else {
System.arraycopy (s.window, r, s.window, q, c);
q += c;
r += c;
c = 0;
}break;
} else if ((e & 64) == 0) {
t += tp[tp_index_t_3 + 2];
t += (b & JZ.InfCodes.inflate_mask[e]);
tp_index_t_3 = (tp_index + t) * 3;
e = tp[tp_index_t_3];
} else {
z.msg = "invalid distance code";
c = z.avail_in - n;
c = (k >> 3) < c ? k >> 3 : c;
n += c;
p -= c;
k -= c << 3;
s.bitb = b;
s.bitk = k;
z.avail_in = n;
z.total_in += p - z.next_in_index;
z.next_in_index = p;
s.write = q;
return -3;
}} while (true);
break;
}if ((e & 64) == 0) {
t += tp[tp_index_t_3 + 2];
t += (b & JZ.InfCodes.inflate_mask[e]);
tp_index_t_3 = (tp_index + t) * 3;
if ((e = tp[tp_index_t_3]) == 0) {
b >>= (tp[tp_index_t_3 + 1]);
k -= (tp[tp_index_t_3 + 1]);
s.window[q++] = tp[tp_index_t_3 + 2];
m--;
break;
}} else if ((e & 32) != 0) {
c = z.avail_in - n;
c = (k >> 3) < c ? k >> 3 : c;
n += c;
p -= c;
k -= c << 3;
s.bitb = b;
s.bitk = k;
z.avail_in = n;
z.total_in += p - z.next_in_index;
z.next_in_index = p;
s.write = q;
return 1;
} else {
z.msg = "invalid literal/length code";
c = z.avail_in - n;
c = (k >> 3) < c ? k >> 3 : c;
n += c;
p -= c;
k -= c << 3;
s.bitb = b;
s.bitk = k;
z.avail_in = n;
z.total_in += p - z.next_in_index;
z.next_in_index = p;
s.write = q;
return -3;
}} while (true);
} while (m >= 258 && n >= 10);
c = z.avail_in - n;
c = (k >> 3) < c ? k >> 3 : c;
n += c;
p -= c;
k -= c << 3;
s.bitb = b;
s.bitk = k;
z.avail_in = n;
z.total_in += p - z.next_in_index;
z.next_in_index = p;
s.write = q;
return 0;
}, "~N,~N,~A,~N,~A,~N,JZ.InfBlocks,JZ.ZStream");
Clazz_defineStatics (c$,
"inflate_mask", [0x00000000, 0x00000001, 0x00000003, 0x00000007, 0x0000000f, 0x0000001f, 0x0000003f, 0x0000007f, 0x000000ff, 0x000001ff, 0x000003ff, 0x000007ff, 0x00000fff, 0x00001fff, 0x00003fff, 0x00007fff, 0x0000ffff],
"Z_OK", 0,
"Z_STREAM_END", 1,
"Z_STREAM_ERROR", -2,
"Z_DATA_ERROR", -3,
"START", 0,
"LEN", 1,
"LENEXT", 2,
"DIST", 3,
"DISTEXT", 4,
"COPY", 5,
"LIT", 6,
"WASH", 7,
"END", 8,
"BADCODE", 9);
Clazz_declarePackage ("java.util.zip");
Clazz_load (["java.io.FilterInputStream"], "java.util.zip.CheckedInputStream", null, function () {
c$ = Clazz_decorateAsClass (function () {
this.cksum = null;
Clazz_instantialize (this, arguments);
}, java.util.zip, "CheckedInputStream", java.io.FilterInputStream);
Clazz_makeConstructor (c$, 
function ($in, cksum) {
Clazz_superConstructor (this, java.util.zip.CheckedInputStream, [$in]);
this.cksum = cksum;
}, "java.io.InputStream,JZ.Checksum");
$_V(c$, "readByteAsInt", 
function () {
var b = this.$in.readByteAsInt ();
if (b != -1) {
this.cksum.updateByteAsInt (b);
}return b;
});
$_V(c$, "read", 
function (buf, off, len) {
len = this.$in.read (buf, off, len);
if (len != -1) {
this.cksum.update (buf, off, len);
}return len;
}, "~A,~N,~N");
$_V(c$, "skip", 
function (n) {
var buf =  Clazz_newByteArray (512, 0);
var total = 0;
while (total < n) {
var len = n - total;
len = this.read (buf, 0, len < buf.length ? len : buf.length);
if (len == -1) {
return total;
}total += len;
}
return total;
}, "~N");
$_M(c$, "getChecksum", 
function () {
return this.cksum;
});
});
Clazz_declarePackage ("java.util.zip");
Clazz_load (["JZ.Inflater"], "java.util.zip.Inflater", null, function () {
c$ = Clazz_declareType (java.util.zip, "Inflater", JZ.Inflater);
$_M(c$, "initialize", 
function (nowrap) {
return this.init (0, nowrap);
}, "~B");
});
Clazz_declarePackage ("java.util.zip");
Clazz_load (["java.io.IOException"], "java.util.zip.ZipException", null, function () {
c$ = Clazz_declareType (java.util.zip, "ZipException", java.io.IOException);
});
Clazz_declarePackage ("java.util.zip");
c$ = Clazz_declareInterface (java.util.zip, "ZipConstants");
Clazz_defineStatics (c$,
"LOCSIG", 0x04034b50,
"EXTSIG", 0x08074b50,
"CENSIG", 0x02014b50,
"ENDSIG", 0x06054b50,
"LOCHDR", 30,
"EXTHDR", 16,
"CENHDR", 46,
"ENDHDR", 22,
"LOCVER", 4,
"LOCFLG", 6,
"LOCHOW", 8,
"LOCTIM", 10,
"LOCCRC", 14,
"LOCSIZ", 18,
"LOCLEN", 22,
"LOCNAM", 26,
"LOCEXT", 28,
"EXTCRC", 4,
"EXTSIZ", 8,
"EXTLEN", 12,
"CENVEM", 4,
"CENVER", 6,
"CENFLG", 8,
"CENHOW", 10,
"CENTIM", 12,
"CENCRC", 16,
"CENSIZ", 20,
"CENLEN", 24,
"CENNAM", 28,
"CENEXT", 30,
"CENCOM", 32,
"CENDSK", 34,
"CENATT", 36,
"CENATX", 38,
"CENOFF", 42,
"ENDSUB", 8,
"ENDTOT", 10,
"ENDSIZ", 12,
"ENDOFF", 16,
"ENDCOM", 20);
Clazz_declarePackage ("java.util.zip");
Clazz_load (["java.util.zip.ZipConstants"], "java.util.zip.ZipEntry", ["java.lang.IllegalArgumentException", "$.InternalError", "$.NullPointerException", "java.util.Date"], function () {
c$ = Clazz_decorateAsClass (function () {
this.offset = 0;
this.name = null;
this.time = -1;
this.crc = -1;
this.size = -1;
this.csize = -1;
this.method = -1;
this.flag = 0;
this.extra = null;
this.comment = null;
Clazz_instantialize (this, arguments);
}, java.util.zip, "ZipEntry", null, [java.util.zip.ZipConstants, Cloneable]);
Clazz_makeConstructor (c$, 
function (name) {
if (name == null) {
throw  new NullPointerException ();
}if (name.length > 0xFFFF) {
throw  new IllegalArgumentException ("entry name too long");
}this.name = name;
}, "~S");
$_M(c$, "getName", 
function () {
return this.name;
});
$_M(c$, "setTime", 
function (time) {
this.time = java.util.zip.ZipEntry.javaToDosTime (time);
}, "~N");
$_M(c$, "getTime", 
function () {
return this.time != -1 ? java.util.zip.ZipEntry.dosToJavaTime (this.time) : -1;
});
$_M(c$, "setSize", 
function (size) {
if (size < 0) {
throw  new IllegalArgumentException ("invalid entry size");
}this.size = size;
}, "~N");
$_M(c$, "getSize", 
function () {
return this.size;
});
$_M(c$, "getCompressedSize", 
function () {
return this.csize;
});
$_M(c$, "setCompressedSize", 
function (csize) {
this.csize = csize;
}, "~N");
$_M(c$, "setCrc", 
function (crc) {
if (crc < 0 || crc > 0xFFFFFFFF) {
throw  new IllegalArgumentException ("invalid entry crc-32");
}this.crc = crc;
}, "~N");
$_M(c$, "getCrc", 
function () {
return this.crc;
});
$_M(c$, "setMethod", 
function (method) {
if (method != 0 && method != 8) {
throw  new IllegalArgumentException ("invalid compression method");
}this.method = method;
}, "~N");
$_M(c$, "getMethod", 
function () {
return this.method;
});
$_M(c$, "setExtra", 
function (extra) {
if (extra != null && extra.length > 0xFFFF) {
throw  new IllegalArgumentException ("invalid extra field length");
}this.extra = extra;
}, "~A");
$_M(c$, "getExtra", 
function () {
return this.extra;
});
$_M(c$, "setComment", 
function (comment) {
this.comment = comment;
}, "~S");
$_M(c$, "getComment", 
function () {
return this.comment;
});
$_M(c$, "isDirectory", 
function () {
return this.name.endsWith ("/");
});
$_V(c$, "toString", 
function () {
return this.getName ();
});
c$.dosToJavaTime = $_M(c$, "dosToJavaTime", 
function (dtime) {
var d =  new java.util.Date ((((dtime >> 25) & 0x7f) + 80), (((dtime >> 21) & 0x0f) - 1), ((dtime >> 16) & 0x1f), ((dtime >> 11) & 0x1f), ((dtime >> 5) & 0x3f), ((dtime << 1) & 0x3e));
return d.getTime ();
}, "~N");
c$.javaToDosTime = $_M(c$, "javaToDosTime", 
function (time) {
var d =  new java.util.Date (time);
var year = d.getYear () + 1900;
if (year < 1980) {
return 2162688;
}return (year - 1980) << 25 | (d.getMonth () + 1) << 21 | d.getDate () << 16 | d.getHours () << 11 | d.getMinutes () << 5 | d.getSeconds () >> 1;
}, "~N");
$_V(c$, "hashCode", 
function () {
return this.name.hashCode ();
});
$_M(c$, "clone", 
function () {
try {
var e = Clazz_superCall (this, java.util.zip.ZipEntry, "clone", []);
if (this.extra != null) {
e.extra =  Clazz_newByteArray (this.extra.length, 0);
System.arraycopy (this.extra, 0, e.extra, 0, this.extra.length);
}return e;
} catch (e) {
if (Clazz_exceptionOf (e, CloneNotSupportedException)) {
throw  new InternalError ();
} else {
throw e;
}
}
});
Clazz_defineStatics (c$,
"STORED", 0,
"DEFLATED", 8);
});
Clazz_declarePackage ("java.util.zip");
c$ = Clazz_declareType (java.util.zip, "ZipConstants64");
Clazz_defineStatics (c$,
"ZIP64_ENDSIG", 0x06064b50,
"ZIP64_LOCSIG", 0x07064b50,
"ZIP64_ENDHDR", 56,
"ZIP64_LOCHDR", 20,
"ZIP64_EXTHDR", 24,
"ZIP64_EXTID", 0x0001,
"ZIP64_MAGICCOUNT", 0xFFFF,
"ZIP64_MAGICVAL", 0xFFFFFFFF,
"ZIP64_ENDLEN", 4,
"ZIP64_ENDVEM", 12,
"ZIP64_ENDVER", 14,
"ZIP64_ENDNMD", 16,
"ZIP64_ENDDSK", 20,
"ZIP64_ENDTOD", 24,
"ZIP64_ENDTOT", 32,
"ZIP64_ENDSIZ", 40,
"ZIP64_ENDOFF", 48,
"ZIP64_ENDEXT", 56,
"ZIP64_LOCDSK", 4,
"ZIP64_LOCOFF", 8,
"ZIP64_LOCTOT", 16,
"ZIP64_EXTCRC", 4,
"ZIP64_EXTSIZ", 8,
"ZIP64_EXTLEN", 16,
"EFS", 0x800);
Clazz_declarePackage ("java.util.zip");
Clazz_load (["java.util.zip.InflaterInputStream", "$.ZipConstants", "$.CRC32"], "java.util.zip.ZipInputStream", ["java.io.EOFException", "$.IOException", "$.PushbackInputStream", "java.lang.IllegalArgumentException", "$.IndexOutOfBoundsException", "$.Long", "$.NullPointerException", "java.util.zip.Inflater", "$.ZipEntry", "$.ZipException"], function () {
c$ = Clazz_decorateAsClass (function () {
this.entry = null;
this.flag = 0;
this.crc = null;
this.remaining = 0;
this.tmpbuf = null;
this.$closed = false;
this.entryEOF = false;
this.zc = null;
this.byteTest = null;
this.$b = null;
Clazz_instantialize (this, arguments);
}, java.util.zip, "ZipInputStream", java.util.zip.InflaterInputStream, java.util.zip.ZipConstants);
Clazz_prepareFields (c$, function () {
this.crc =  new java.util.zip.CRC32 ();
this.tmpbuf =  Clazz_newByteArray (512, 0);
this.byteTest = [0x20];
this.$b =  Clazz_newByteArray (256, 0);
});
$_M(c$, "ensureOpen", 
function () {
if (this.$closed) {
throw  new java.io.IOException ("Stream closed");
}});
Clazz_makeConstructor (c$, 
function ($in) {
Clazz_superConstructor (this, java.util.zip.ZipInputStream, [ new java.io.PushbackInputStream ($in, 1024), java.util.zip.ZipInputStream.newInflater (), 512]);
var charset = "UTF-8";
try {
 String.instantialize (this.byteTest, charset);
} catch (e) {
if (Clazz_exceptionOf (e, java.io.UnsupportedEncodingException)) {
throw  new NullPointerException ("charset is invalid");
} else {
throw e;
}
}
this.zc = charset;
}, "java.io.InputStream");
c$.newInflater = $_M(c$, "newInflater", 
function () {
return  new java.util.zip.Inflater ().init (0, true);
});
$_M(c$, "getNextEntry", 
function () {
this.ensureOpen ();
if (this.entry != null) {
this.closeEntry ();
}this.crc.reset ();
this.inflater = this.inf = java.util.zip.ZipInputStream.newInflater ();
if ((this.entry = this.readLOC ()) == null) {
return null;
}if (this.entry.method == 0) {
this.remaining = this.entry.size;
}this.entryEOF = false;
return this.entry;
});
$_M(c$, "closeEntry", 
function () {
this.ensureOpen ();
while (this.read (this.tmpbuf, 0, this.tmpbuf.length) != -1) {
}
this.entryEOF = true;
});
$_V(c$, "available", 
function () {
this.ensureOpen ();
return (this.entryEOF ? 0 : 1);
});
$_V(c$, "read", 
function (b, off, len) {
this.ensureOpen ();
if (off < 0 || len < 0 || off > b.length - len) {
throw  new IndexOutOfBoundsException ();
} else if (len == 0) {
return 0;
}if (this.entry == null) {
return -1;
}switch (this.entry.method) {
case 8:
len = this.readInf (b, off, len);
if (len == -1) {
this.readEnd (this.entry);
this.entryEOF = true;
this.entry = null;
} else {
this.crc.update (b, off, len);
}return len;
case 0:
if (this.remaining <= 0) {
this.entryEOF = true;
this.entry = null;
return -1;
}if (len > this.remaining) {
len = this.remaining;
}len = this.$in.read (b, off, len);
if (len == -1) {
throw  new java.util.zip.ZipException ("unexpected EOF");
}this.crc.update (b, off, len);
this.remaining -= len;
if (this.remaining == 0 && this.entry.crc != this.crc.getValue ()) {
throw  new java.util.zip.ZipException ("invalid entry CRC (expected 0x" + Long.toHexString (this.entry.crc) + " but got 0x" + Long.toHexString (this.crc.getValue ()) + ")");
}return len;
default:
throw  new java.util.zip.ZipException ("invalid compression method");
}
}, "~A,~N,~N");
$_V(c$, "skip", 
function (n) {
if (n < 0) {
throw  new IllegalArgumentException ("negative skip length");
}this.ensureOpen ();
var max = Math.min (n, 2147483647);
var total = 0;
while (total < max) {
var len = max - total;
if (len > this.tmpbuf.length) {
len = this.tmpbuf.length;
}len = this.read (this.tmpbuf, 0, len);
if (len == -1) {
this.entryEOF = true;
break;
}total += len;
}
return total;
}, "~N");
$_M(c$, "close", 
function () {
if (!this.$closed) {
Clazz_superCall (this, java.util.zip.ZipInputStream, "close", []);
this.$closed = true;
}});
$_M(c$, "readLOC", 
function () {
try {
this.readFully (this.tmpbuf, 0, 30);
} catch (e) {
if (Clazz_exceptionOf (e, java.io.EOFException)) {
return null;
} else {
throw e;
}
}
if (java.util.zip.ZipInputStream.get32 (this.tmpbuf, 0) != 67324752) {
return null;
}this.flag = java.util.zip.ZipInputStream.get16 (this.tmpbuf, 6);
var len = java.util.zip.ZipInputStream.get16 (this.tmpbuf, 26);
var blen = this.$b.length;
if (len > blen) {
do blen = blen * 2;
 while (len > blen);
this.$b =  Clazz_newByteArray (blen, 0);
}this.readFully (this.$b, 0, len);
var e = this.createZipEntry (((this.flag & 2048) != 0) ? this.toStringUTF8 (this.$b, len) : this.toStringb2 (this.$b, len));
if ((this.flag & 1) == 1) {
throw  new java.util.zip.ZipException ("encrypted ZIP entry not supported");
}e.method = java.util.zip.ZipInputStream.get16 (this.tmpbuf, 8);
e.time = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 10);
if ((this.flag & 8) == 8) {
if (e.method != 8) {
throw  new java.util.zip.ZipException ("only DEFLATED entries can have EXT descriptor");
}} else {
e.crc = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 14);
e.csize = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 18);
e.size = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 22);
}len = java.util.zip.ZipInputStream.get16 (this.tmpbuf, 28);
if (len > 0) {
var bb =  Clazz_newByteArray (len, 0);
this.readFully (bb, 0, len);
e.setExtra (bb);
if (e.csize == 4294967295 || e.size == 4294967295) {
var off = 0;
while (off + 4 < len) {
var sz = java.util.zip.ZipInputStream.get16 (bb, off + 2);
if (java.util.zip.ZipInputStream.get16 (bb, off) == 1) {
off += 4;
if (sz < 16 || (off + sz) > len) {
return e;
}e.size = java.util.zip.ZipInputStream.get64 (bb, off);
e.csize = java.util.zip.ZipInputStream.get64 (bb, off + 8);
break;
}off += (sz + 4);
}
}}return e;
});
$_M(c$, "toStringUTF8", 
function (b2, len) {
try {
return  String.instantialize (b2, 0, len, this.zc);
} catch (e) {
if (Clazz_exceptionOf (e, java.io.UnsupportedEncodingException)) {
return this.toStringb2 (b2, len);
} else {
throw e;
}
}
}, "~A,~N");
$_M(c$, "toStringb2", 
function (b2, len) {
return  String.instantialize (b2, 0, len);
}, "~A,~N");
$_M(c$, "createZipEntry", 
function (name) {
return  new java.util.zip.ZipEntry (name);
}, "~S");
$_M(c$, "readEnd", 
function (e) {
var n = this.inf.getAvailIn ();
if (n > 0) {
(this.$in).unread (this.buf, this.len - n, n);
this.eof = false;
}if ((this.flag & 8) == 8) {
if (this.inf.getTotalOut () > 4294967295 || this.inf.getTotalIn () > 4294967295) {
this.readFully (this.tmpbuf, 0, 24);
var sig = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 0);
if (sig != 134695760) {
e.crc = sig;
e.csize = java.util.zip.ZipInputStream.get64 (this.tmpbuf, 4);
e.size = java.util.zip.ZipInputStream.get64 (this.tmpbuf, 12);
(this.$in).unread (this.tmpbuf, 19, 4);
} else {
e.crc = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 4);
e.csize = java.util.zip.ZipInputStream.get64 (this.tmpbuf, 8);
e.size = java.util.zip.ZipInputStream.get64 (this.tmpbuf, 16);
}} else {
this.readFully (this.tmpbuf, 0, 16);
var sig = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 0);
if (sig != 134695760) {
e.crc = sig;
e.csize = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 4);
e.size = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 8);
(this.$in).unread (this.tmpbuf, 11, 4);
} else {
e.crc = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 4);
e.csize = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 8);
e.size = java.util.zip.ZipInputStream.get32 (this.tmpbuf, 12);
}}}if (e.size != this.inf.getTotalOut ()) {
throw  new java.util.zip.ZipException ("invalid entry size (expected " + e.size + " but got " + this.inf.getTotalOut () + " bytes)");
}if (e.csize != this.inf.getTotalIn ()) {
throw  new java.util.zip.ZipException ("invalid entry compressed size (expected " + e.csize + " but got " + this.inf.getTotalIn () + " bytes)");
}if (e.crc != this.crc.getValue ()) {
throw  new java.util.zip.ZipException ("invalid entry CRC (expected 0x" + Long.toHexString (e.crc) + " but got 0x" + Long.toHexString (this.crc.getValue ()) + ")");
}}, "java.util.zip.ZipEntry");
$_M(c$, "readFully", 
function (b, off, len) {
while (len > 0) {
var n = this.$in.read (b, off, len);
if (n == -1) {
throw  new java.io.EOFException ();
}off += n;
len -= n;
}
}, "~A,~N,~N");
c$.get16 = $_M(c$, "get16", 
function (b, off) {
return (b[off] & 0xff) | ((b[off + 1] & 0xff) << 8);
}, "~A,~N");
c$.get32 = $_M(c$, "get32", 
function (b, off) {
return (java.util.zip.ZipInputStream.get16 (b, off) | (java.util.zip.ZipInputStream.get16 (b, off + 2) << 16)) & 0xffffffff;
}, "~A,~N");
c$.get64 = $_M(c$, "get64", 
function (b, off) {
return java.util.zip.ZipInputStream.get32 (b, off) | (java.util.zip.ZipInputStream.get32 (b, off + 4) << 32);
}, "~A,~N");
Clazz_defineStatics (c$,
"STORED", 0,
"DEFLATED", 8);
});
Clazz_load (["java.io.FilterInputStream"], "java.io.PushbackInputStream", ["java.io.IOException", "java.lang.IllegalArgumentException", "$.IndexOutOfBoundsException", "$.NullPointerException"], function () {
c$ = Clazz_decorateAsClass (function () {
this.buf = null;
this.pos = 0;
Clazz_instantialize (this, arguments);
}, java.io, "PushbackInputStream", java.io.FilterInputStream);
$_M(c$, "ensureOpen", 
function () {
if (this.$in == null) throw  new java.io.IOException ("Stream closed");
});
Clazz_makeConstructor (c$, 
function ($in, size) {
Clazz_superConstructor (this, java.io.PushbackInputStream, [$in]);
if (size <= 0) {
throw  new IllegalArgumentException ("size <= 0");
}this.buf =  Clazz_newByteArray (size, 0);
this.pos = size;
}, "java.io.InputStream,~N");
$_V(c$, "readByteAsInt", 
function () {
this.ensureOpen ();
if (this.pos < this.buf.length) {
return this.buf[this.pos++] & 0xff;
}return this.$in.readByteAsInt ();
});
$_V(c$, "read", 
function (b, off, len) {
this.ensureOpen ();
if (b == null) {
throw  new NullPointerException ();
} else if (off < 0 || len < 0 || len > b.length - off) {
throw  new IndexOutOfBoundsException ();
} else if (len == 0) {
return 0;
}var avail = this.buf.length - this.pos;
if (avail > 0) {
if (len < avail) {
avail = len;
}System.arraycopy (this.buf, this.pos, b, off, avail);
this.pos += avail;
off += avail;
len -= avail;
}if (len > 0) {
len = this.$in.read (b, off, len);
if (len == -1) {
return avail == 0 ? -1 : avail;
}return avail + len;
}return avail;
}, "~A,~N,~N");
$_M(c$, "unreadByte", 
function (b) {
this.ensureOpen ();
if (this.pos == 0) {
throw  new java.io.IOException ("Push back buffer is full");
}this.buf[--this.pos] = b;
}, "~N");
$_M(c$, "unread", 
function (b, off, len) {
this.ensureOpen ();
if (len > this.pos) {
throw  new java.io.IOException ("Push back buffer is full");
}this.pos -= len;
System.arraycopy (b, off, this.buf, this.pos, len);
}, "~A,~N,~N");
$_V(c$, "available", 
function () {
this.ensureOpen ();
var n = this.buf.length - this.pos;
var avail = this.$in.available ();
return n > (2147483647 - avail) ? 2147483647 : n + avail;
});
$_V(c$, "skip", 
function (n) {
this.ensureOpen ();
if (n <= 0) {
return 0;
}var pskip = this.buf.length - this.pos;
if (pskip > 0) {
if (n < pskip) {
pskip = n;
}this.pos += pskip;
n -= pskip;
}if (n > 0) {
pskip += this.$in.skip (n);
}return pskip;
}, "~N");
$_V(c$, "markSupported", 
function () {
return false;
});
$_V(c$, "mark", 
function (readlimit) {
}, "~N");
$_V(c$, "reset", 
function () {
throw  new java.io.IOException ("mark/reset not supported");
});
$_V(c$, "close", 
function () {
if (this.$in == null) return;
this.$in.close ();
this.$in = null;
this.buf = null;
});
});
Clazz_declarePackage ("J.io2");
Clazz_load (["java.util.zip.ZipInputStream", "javajs.api.ZInputStream"], "J.io2.JmolZipInputStream", null, function () {
c$ = Clazz_declareType (J.io2, "JmolZipInputStream", java.util.zip.ZipInputStream, javajs.api.ZInputStream);
});
})(Clazz
,Clazz.newArray
,Clazz.newBooleanArray
,Clazz.newByteArray
,Clazz.newCharArray
,Clazz.newDoubleArray
,Clazz.newFloatArray
,Clazz.newIntArray
,Clazz.newLongArray
,Clazz.newShortArray
,Clazz.prepareCallback
,Clazz.decorateAsClass
,Clazz.isClassDefined
,Clazz.defineEnumConstant
,Clazz.cloneFinals
,Clazz.inheritArgs
,Clazz.pu$h
,Clazz.declareInterface
,Clazz.declarePackage
,Clazz.makeConstructor
,Clazz.overrideConstructor
,Clazz.load
,Clazz.defineMethod
,Clazz.innerTypeInstance
,Clazz.instanceOf
,Clazz.p0p
,Clazz.makeFunction
,Clazz.superConstructor
,Clazz.defineStatics
,Clazz.registerSerializableFields
,Clazz.declareType
,Clazz.superCall
,Clazz.overrideMethod
,Clazz.declareAnonymous
,Clazz.checkPrivateMethod
,Clazz.prepareFields
,Clazz.instantialize
,Clazz.doubleToInt
,Clazz.declarePackage
,Clazz.instanceOf
,Clazz.load
,Clazz.instantialize
,Clazz.decorateAsClass
,Clazz.floatToInt
,Clazz.makeConstructor
,Clazz.defineEnumConstant
,Clazz.exceptionOf
,Clazz.newIntArray
,Clazz.defineStatics
,Clazz.newFloatArray
,Clazz.declareType
,Clazz.prepareFields
,Clazz.superConstructor
,Clazz.newByteArray
,Clazz.declareInterface
,Clazz.p0p
,Clazz.pu$h
,Clazz.newShortArray
,Clazz.innerTypeInstance
,Clazz.isClassDefined
,Clazz.prepareCallback
,Clazz.newArray
,Clazz.castNullAs
,Clazz.floatToShort
,Clazz.superCall
,Clazz.decorateAsType
,Clazz.newBooleanArray
,Clazz.newCharArray
,Clazz.implementOf
,Clazz.newDoubleArray
,Clazz.overrideConstructor
,Clazz.supportsNativeObject
,Clazz.extendedObjectMethods
,Clazz.callingStackTraces
,Clazz.clone
,Clazz.doubleToShort
,Clazz.innerFunctions
,Clazz.getInheritedLevel
,Clazz.getParamsType
,Clazz.isAF
,Clazz.isAI
,Clazz.isAS
,Clazz.isASS
,Clazz.isAP
,Clazz.isAFloat
,Clazz.isAII
,Clazz.isAFF
,Clazz.isAFFF
,Clazz.tryToSearchAndExecute
,Clazz.getStackTrace
,Clazz.inheritArgs
);
