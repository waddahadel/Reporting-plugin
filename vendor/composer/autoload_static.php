<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb991cd2aee83bdc338dc1b2d78ab0519
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'srag\\JasperReport\\' => 18,
            'srag\\DIC\\' => 9,
            'setasign\\Fpdi\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'srag\\JasperReport\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/jasperreport/src',
        ),
        'srag\\DIC\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/dic/src',
        ),
        'setasign\\Fpdi\\' => 
        array (
            0 => __DIR__ . '/..' . '/setasign/fpdi/src',
        ),
    );

    public static $classMap = array (
        'ActiveRecord' => __DIR__ . '/../..' . '/../../../../../../../Services/ActiveRecord/class.ActiveRecord.php',
        'ilCSVWriter' => __DIR__ . '/../..' . '/../../../../../../../Services/Utilities/classes/class.ilCSVWriter.php',
        'ilCheckboxInputGUI' => __DIR__ . '/../..' . '/../../../../../../../Services/Form/classes/class.ilCheckboxInputGUI.php',
        'ilComponent' => __DIR__ . '/../..' . '/../../../../../../../Services/Component/classes/class.ilComponent.php',
        'ilCourseParticipant' => __DIR__ . '/../..' . '/../../../../../../../Modules/Course/classes/class.ilCourseParticipant.php',
        'ilDateTime' => __DIR__ . '/../..' . '/../../../../../../../Services/Calendar/classes/class.ilDateTime.php',
        'ilDateTimeInputGUI' => __DIR__ . '/../..' . '/../../../../../../../Services/Form/classes/class.ilDateTimeInputGUI.php',
        'ilObjOrgUnit' => __DIR__ . '/../..' . '/../../../../../../../Modules/OrgUnit/classes/class.ilObjOrgUnit.php',
        'ilObjOrgUnitTree' => __DIR__ . '/../..' . '/../../../../../../../Modules/OrgUnit/classes/class.ilObjOrgUnitTree.php',
        'ilObject2' => __DIR__ . '/../..' . '/../../../../../../../Services/Object/classes/class.ilObject2.php',
        'ilObjectGUI' => __DIR__ . '/../..' . '/../../../../../../../Services/Object/classes/class.ilObjectGUI.php',
        'ilPluginConfigGUI' => __DIR__ . '/../..' . '/../../../../../../../Services/Component/classes/class.ilPluginConfigGUI.php',
        'ilPropertyFormGUI' => __DIR__ . '/../..' . '/../../../../../../../Services/Form/classes/class.ilPropertyFormGUI.php',
        'ilReportingAccess' => __DIR__ . '/../..' . '/classes/class.ilReportingAccess.php',
        'ilReportingAdjustPermissionCron' => __DIR__ . '/../..' . '/classes/class.ilReportingAdjustPermissionCron.php',
        'ilReportingConfig' => __DIR__ . '/../..' . '/classes/class.ilReportingConfig.php',
        'ilReportingConfigGUI' => __DIR__ . '/../..' . '/classes/class.ilReportingConfigGUI.php',
        'ilReportingCoursesPerUserGUI' => __DIR__ . '/../..' . '/classes/CoursesPerUser/class.ilReportingCoursesPerUserGUI.php',
        'ilReportingCoursesPerUserLPGUI' => __DIR__ . '/../..' . '/classes/CoursesPerUserLP/class.ilReportingCoursesPerUserLPGUI.php',
        'ilReportingCoursesPerUserLPModel' => __DIR__ . '/../..' . '/classes/CoursesPerUserLP/class.ilReportingCoursesPerUserLPModel.php',
        'ilReportingCoursesPerUserLPPdfExport' => __DIR__ . '/../..' . '/classes/CoursesPerUserLP/class.ilReportingCoursesPerUserLPPdfExport.php',
        'ilReportingCoursesPerUserLPReportTableGUI' => __DIR__ . '/../..' . '/classes/CoursesPerUserLP/class.ilReportingCoursesPerUserLPReportTableGUI.php',
        'ilReportingCoursesPerUserLPSearchTableGUI' => __DIR__ . '/../..' . '/classes/CoursesPerUserLP/class.ilReportingCoursesPerUserLPSearchTableGUI.php',
        'ilReportingCoursesPerUserModel' => __DIR__ . '/../..' . '/classes/CoursesPerUser/class.ilReportingCoursesPerUserModel.php',
        'ilReportingCoursesPerUserPdfExport' => __DIR__ . '/../..' . '/classes/CoursesPerUser/class.ilReportingCoursesPerUserPdfExport.php',
        'ilReportingCoursesPerUserReportTableGUI' => __DIR__ . '/../..' . '/classes/CoursesPerUser/class.ilReportingCoursesPerUserReportTableGUI.php',
        'ilReportingCoursesPerUserSearchTableGUI' => __DIR__ . '/../..' . '/classes/CoursesPerUser/class.ilReportingCoursesPerUserSearchTableGUI.php',
        'ilReportingFormatter' => __DIR__ . '/../..' . '/classes/class.ilReportingFormatter.php',
        'ilReportingGUI' => __DIR__ . '/../..' . '/classes/class.ilReportingGUI.php',
        'ilReportingModel' => __DIR__ . '/../..' . '/classes/class.ilReportingModel.php',
        'ilReportingPdfExport' => __DIR__ . '/../..' . '/classes/class.ilReportingPdfExport.php',
        'ilReportingPlugin' => __DIR__ . '/../..' . '/classes/class.ilReportingPlugin.php',
        'ilReportingReportTableGUI' => __DIR__ . '/../..' . '/classes/class.ilReportingReportTableGUI.php',
        'ilReportingSearchTableGUI' => __DIR__ . '/../..' . '/classes/class.ilReportingSearchTableGUI.php',
        'ilReportingUIHookGUI' => __DIR__ . '/../..' . '/classes/class.ilReportingUIHookGUI.php',
        'ilReportingUsersPerCourseGUI' => __DIR__ . '/../..' . '/classes/UsersPerCourse/class.ilReportingUsersPerCourseGUI.php',
        'ilReportingUsersPerCourseLPGUI' => __DIR__ . '/../..' . '/classes/UsersPerCourseLP/class.ilReportingUsersPerCourseLPGUI.php',
        'ilReportingUsersPerCourseLPModel' => __DIR__ . '/../..' . '/classes/UsersPerCourseLP/class.ilReportingUsersPerCourseLPModel.php',
        'ilReportingUsersPerCourseLPPdfExport' => __DIR__ . '/../..' . '/classes/UsersPerCourseLP/class.ilReportingUsersPerCourseLPPdfExport.php',
        'ilReportingUsersPerCourseLPReportTableGUI' => __DIR__ . '/../..' . '/classes/UsersPerCourseLP/class.ilReportingUsersPerCourseLPReportTableGUI.php',
        'ilReportingUsersPerCourseLPSearchTableGUI' => __DIR__ . '/../..' . '/classes/UsersPerCourseLP/class.ilReportingUsersPerCourseLPSearchTableGUI.php',
        'ilReportingUsersPerCourseModel' => __DIR__ . '/../..' . '/classes/UsersPerCourse/class.ilReportingUsersPerCourseModel.php',
        'ilReportingUsersPerCoursePdfExport' => __DIR__ . '/../..' . '/classes/UsersPerCourse/class.ilReportingUsersPerCoursePdfExport.php',
        'ilReportingUsersPerCourseReportTableGUI' => __DIR__ . '/../..' . '/classes/UsersPerCourse/class.ilReportingUsersPerCourseReportTableGUI.php',
        'ilReportingUsersPerCourseSearchTableGUI' => __DIR__ . '/../..' . '/classes/UsersPerCourse/class.ilReportingUsersPerCourseSearchTableGUI.php',
        'ilReportingUsersPerTestGUI' => __DIR__ . '/../..' . '/classes/UsersPerTest/class.ilReportingUsersPerTestGUI.php',
        'ilReportingUsersPerTestModel' => __DIR__ . '/../..' . '/classes/UsersPerTest/class.ilReportingUsersPerTestModel.php',
        'ilReportingUsersPerTestPdfExport' => __DIR__ . '/../..' . '/classes/UsersPerTest/class.ilReportingUsersPerTestPdfExport.php',
        'ilReportingUsersPerTestReportTableGUI' => __DIR__ . '/../..' . '/classes/UsersPerTest/class.ilReportingUsersPerTestReportTableGUI.php',
        'ilReportingUsersPerTestSearchTableGUI' => __DIR__ . '/../..' . '/classes/UsersPerTest/class.ilReportingUsersPerTestSearchTableGUI.php',
        'ilSelectInputGUI' => __DIR__ . '/../..' . '/../../../../../../../Services/Form/classes/class.ilSelectInputGUI.php',
        'ilTable2GUI' => __DIR__ . '/../..' . '/../../../../../../../Services/Table/classes/class.ilTable2GUI.php',
        'ilTextInputGUI' => __DIR__ . '/../..' . '/../../../../../../../Services/Form/classes/class.ilTextInputGUI.php',
        'ilUIHookPluginGUI' => __DIR__ . '/../..' . '/../../../../../../../Services/UIComponent/classes/class.ilUIHookPluginGUI.php',
        'ilUIPluginRouterGUI' => __DIR__ . '/../..' . '/../../../../../../../Services/UIComponent/classes/class.ilUIPluginRouterGUI.php',
        'ilUserInterfaceHookPlugin' => __DIR__ . '/../..' . '/../../../../../../../Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php',
        'setasign\\Fpdi\\FpdfTpl' => __DIR__ . '/..' . '/setasign/fpdi/src/FpdfTpl.php',
        'setasign\\Fpdi\\Fpdi' => __DIR__ . '/..' . '/setasign/fpdi/src/Fpdi.php',
        'setasign\\Fpdi\\FpdiException' => __DIR__ . '/..' . '/setasign/fpdi/src/FpdiException.php',
        'setasign\\Fpdi\\FpdiTrait' => __DIR__ . '/..' . '/setasign/fpdi/src/FpdiTrait.php',
        'setasign\\Fpdi\\PdfParser\\CrossReference\\AbstractReader' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/CrossReference/AbstractReader.php',
        'setasign\\Fpdi\\PdfParser\\CrossReference\\CrossReference' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/CrossReference/CrossReference.php',
        'setasign\\Fpdi\\PdfParser\\CrossReference\\CrossReferenceException' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/CrossReference/CrossReferenceException.php',
        'setasign\\Fpdi\\PdfParser\\CrossReference\\FixedReader' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/CrossReference/FixedReader.php',
        'setasign\\Fpdi\\PdfParser\\CrossReference\\LineReader' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/CrossReference/LineReader.php',
        'setasign\\Fpdi\\PdfParser\\CrossReference\\ReaderInterface' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/CrossReference/ReaderInterface.php',
        'setasign\\Fpdi\\PdfParser\\Filter\\Ascii85' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Filter/Ascii85.php',
        'setasign\\Fpdi\\PdfParser\\Filter\\Ascii85Exception' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Filter/Ascii85Exception.php',
        'setasign\\Fpdi\\PdfParser\\Filter\\AsciiHex' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Filter/AsciiHex.php',
        'setasign\\Fpdi\\PdfParser\\Filter\\FilterException' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Filter/FilterException.php',
        'setasign\\Fpdi\\PdfParser\\Filter\\FilterInterface' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Filter/FilterInterface.php',
        'setasign\\Fpdi\\PdfParser\\Filter\\Flate' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Filter/Flate.php',
        'setasign\\Fpdi\\PdfParser\\Filter\\FlateException' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Filter/FlateException.php',
        'setasign\\Fpdi\\PdfParser\\Filter\\Lzw' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Filter/Lzw.php',
        'setasign\\Fpdi\\PdfParser\\Filter\\LzwException' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Filter/LzwException.php',
        'setasign\\Fpdi\\PdfParser\\PdfParser' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/PdfParser.php',
        'setasign\\Fpdi\\PdfParser\\PdfParserException' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/PdfParserException.php',
        'setasign\\Fpdi\\PdfParser\\StreamReader' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/StreamReader.php',
        'setasign\\Fpdi\\PdfParser\\Tokenizer' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Tokenizer.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfArray' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfArray.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfBoolean' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfBoolean.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfDictionary' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfDictionary.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfHexString' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfHexString.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfIndirectObject' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfIndirectObject.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfIndirectObjectReference' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfIndirectObjectReference.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfName' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfName.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfNull' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfNull.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfNumeric' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfNumeric.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfStream' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfStream.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfString' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfString.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfToken' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfToken.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfType' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfType.php',
        'setasign\\Fpdi\\PdfParser\\Type\\PdfTypeException' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfParser/Type/PdfTypeException.php',
        'setasign\\Fpdi\\PdfReader\\DataStructure\\Rectangle' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfReader/DataStructure/Rectangle.php',
        'setasign\\Fpdi\\PdfReader\\Page' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfReader/Page.php',
        'setasign\\Fpdi\\PdfReader\\PageBoundaries' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfReader/PageBoundaries.php',
        'setasign\\Fpdi\\PdfReader\\PdfReader' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfReader/PdfReader.php',
        'setasign\\Fpdi\\PdfReader\\PdfReaderException' => __DIR__ . '/..' . '/setasign/fpdi/src/PdfReader/PdfReaderException.php',
        'setasign\\Fpdi\\TcpdfFpdi' => __DIR__ . '/..' . '/setasign/fpdi/src/TcpdfFpdi.php',
        'srag\\DIC\\AbstractDIC' => __DIR__ . '/..' . '/srag/dic/src/AbstractDIC.php',
        'srag\\DIC\\DICException' => __DIR__ . '/..' . '/srag/dic/src/DICException.php',
        'srag\\DIC\\DICInterface' => __DIR__ . '/..' . '/srag/dic/src/DICInterface.php',
        'srag\\DIC\\DICStatic' => __DIR__ . '/..' . '/srag/dic/src/DICStatic.php',
        'srag\\DIC\\DICTrait' => __DIR__ . '/..' . '/srag/dic/src/DICTrait.php',
        'srag\\DIC\\LegacyDIC' => __DIR__ . '/..' . '/srag/dic/src/LegacyDIC.php',
        'srag\\DIC\\NewDIC' => __DIR__ . '/..' . '/srag/dic/src/NewDIC.php',
        'srag\\JasperReport\\JasperReport' => __DIR__ . '/..' . '/srag/jasperreport/src/class.JasperReport.php',
        'srag\\JasperReport\\JasperReportException' => __DIR__ . '/..' . '/srag/jasperreport/src/class.JasperReportException.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb991cd2aee83bdc338dc1b2d78ab0519::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb991cd2aee83bdc338dc1b2d78ab0519::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb991cd2aee83bdc338dc1b2d78ab0519::$classMap;

        }, null, ClassLoader::class);
    }
}
