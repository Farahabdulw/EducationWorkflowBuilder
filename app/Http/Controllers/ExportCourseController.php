<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use ZipArchive;
use App\Models\Course;

class ExportCourseController extends Controller
{
    public $course;
    public function export_course($id)
    {

        $this->course = Course::with([
            'department',
            'college',
            'preRequisites',
            'coRequisites',
            'mainObjective',
            'teachingMode',
            'contactHours',
            'knowledge',
            'skills',
            'values',
            'content',
            'studentsAssessment',
            'facilitiesAndEquipment',
            'assessmentQuality',
            'students'
        ])->find($id);

        if (!$this->course)
            return view('404');

        $zip = new ZipArchive;
        $loadedDocPath = storage_path('app/private/course-template.docx');
        $newDocPath = storage_path('app/private/coursezip.docx');

        if ($zip->open($loadedDocPath) === true) {
            $content = $zip->getFromName('word/document.xml');
            $content = $this->PopulateFirstPage($content);
            $content = $this->PopulateSecondPage($content);

            $newZip = new ZipArchive;
            $newZip->open($newDocPath, ZipArchive::CREATE);

            $newZip->addFromString('word/document.xml', $content);

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if ($filename !== 'word/document.xml') {
                    $newZip->addFromString($filename, $zip->getFromIndex($i));
                }
            }

            $newZip->close();
            $zip->close();

            return response()->download($newDocPath, 'coursezip.docx');
        } else {
            return response()->json(['error' => 'Failed to open the Word document.']);
        }
    }

    private function PopulateFirstPage($content)
    {
        $lastRevisionDate = Carbon::parse(json_decode($this->course->last_revision)->date)->format('Y/m/d');
        $newContent = str_replace('Enter Course Title', $this->course->title, $content);
        $newContent = str_replace('Enter Course Code', $this->course->code, $newContent);
        $newContent = str_replace('Enter Program Name', $this->course->program, $newContent);
        $newContent = str_replace('Enter Department Name', $this->course->department->name, $newContent);
        $newContent = str_replace('Enter College Name', $this->course->college->name, $newContent);
        $newContent = str_replace('Enter Institution Name', $this->course->institution, $newContent);
        $newContent = str_replace('Course Specification Version Number', $this->course->version, $newContent);
        $newContent = str_replace('Pick Revision Date', $lastRevisionDate, $newContent);
        return $newContent;
    }
    private function PopulateSecondPage($content)
    {
        $type = json_decode($this->course->type);

        $searchUniversity = '/☒(.*?University)/s';
        $typeValueUniversity = ($type->university === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchUniversity, function ($matches) use ($typeValueUniversity) {
            return $typeValueUniversity . $matches[1];
        }, $content, 1);

        $searchCollege = '/☐(.*?College)/s';
        $typeValueCollege = ($type->college === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchCollege, function ($matches) use ($typeValueCollege) {
            return $typeValueCollege . $matches[1];
        }, $content, 1);

        $searchDepartment = '/☒(.*?Department)/s';
        $typeValueDepartment = ($type->department === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchDepartment, function ($matches) use ($typeValueDepartment) {
            return $typeValueDepartment . $matches[1];
        }, $content, 1);

        $searchTrack = '/☐(.*?Track)/s';
        $typeValueTrack = ($type->track === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchTrack, function ($matches) use ($typeValueTrack) {
            return $typeValueTrack . $matches[1];
        }, $content, 1);

        $searchOthers = '/☐(.*?Others)/s';
        $typeValueOthers = ($type->others === "true") ? '☐' : '☒';
        $content = preg_replace_callback($searchOthers, function ($matches) use ($typeValueOthers) {
            return $typeValueOthers . $matches[1];
        }, $content, 1);

        $enrollment = json_decode($this->course->enrollment);
        $checkCategory = $enrollment->required ? 'Required' : 'Elective';
        $content = preg_replace('/☐(.*?' . $checkCategory . ')/s', '☒$1', $content, 1);

        $content = preg_replace('/(Credit hours:.*?\()([^)]+)(\))/s', 'Credit hours: ' . $this->course->credit, $content, 1);

        $content = preg_replace('/year at which this course is offered:(.*?\(.*?\))/s', 'year at which this course is offered: ' . $this->course->level, $content, 1);

        $content = str_replace('$description', $this->course->description, $content);

        $content = $this->replaceRequirements($content, '$pre-requirements', $this->course->preRequisites);
        $content = $this->replaceRequirements($content, '$co-requisites', $this->course->coRequisites);
        $content = $this->replaceRequirements($content, '$main-objective', $this->course->mainObjective);
        $content = $this->addRowsTeachingModes($content, $this->course->teachingMode);
        $content = $this->addRowsContactHours($content, $this->course->contactHours);
        $content = $this->addRowsInstruction($content, $this->course->knowledge, 1);
        $content = $this->addRowsInstruction($content, $this->course->skills, 2);
        $content = $this->addRowsInstruction($content, $this->course->values, 3);
        $content = $this->addRowsContent($content, $this->course->content);
        $content = $this->addRowsAssissment($content, $this->course->studentsAssessment);
        $content = str_replace('$essential', $this->course->essential_references, $content);
        $content = str_replace('$supportive', $this->course->supportive_references, $content);
        $content = str_replace('$electronic', $this->course->electronic_references, $content);
        $content = str_replace('$other', $this->course->other_references, $content);
        $content = $this->addRowsFacilities($content, $this->course->facilitiesAndEquipment);
        $content = $this->addRowsAssessmentQuality($content, $this->course->assessmentQuality);
        $content = str_replace('$approved', $this->course->approved_by, $content);
        $content = str_replace('$refrence_number', $this->course->approval_number, $content);
        $content = str_replace('$Date', $this->course->approval_date, $content);


        return $content;
    }
    private function addRowsTeachingModes($content, $modes)
    {

        $appendTo = '<w:t>Percentage</w:t></w:r></w:p></w:tc></w:tr>';
        $position = strpos($content, $appendTo);
        $rows = '';
        foreach ($modes as $index => $mode) {
            $row = '<w:tr w:rsidR="00B97745" w:rsidRPr="003B6DF4" w14:paraId="1171117D" w14:textId="77777777" w:rsidTr="00DB0E18"><w:trPr><w:trHeight w:val="260"/><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="846" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="5280797A" w14:textId="7D15F51F" w:rsidR="00B97745" w:rsidRPr="00DB0E18" w:rsidRDefault="00DB0E18" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:ind w:left="300"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' .
                $index .
                '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3509" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="25C11AD5" w14:textId="61A8558A" w:rsidR="00B97745" w:rsidRPr="00DB0E18" w:rsidRDefault="00B97745" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="28"/><w:szCs w:val="28"/><w:rtl/></w:rPr></w:pPr><w:r w:rsidRPr="00DB0E18"><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="28"/><w:szCs w:val="28"/></w:rPr><w:t>' . $mode->mode_of_instruction . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2607" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="7C2F2B3A" w14:textId="3E90745B" w:rsidR="00B97745" w:rsidRPr="003B6DF4" w:rsidRDefault="005C11FA" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $mode->contact_hours . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2600" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="389A45B5" w14:textId="71BDF232" w:rsidR="00B97745" w:rsidRPr="003B6DF4" w:rsidRDefault="005C11FA" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/></w:rPr><w:t>' . $mode->percentage . '</w:t></w:r></w:p></w:tc></w:tr>';
            $rows .= $row;

        }

        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        // Concatenate the new rows in between
        $content = $part1 . $rows . $part2;
        return $content;
    }
    private function addRowsContactHours($content, $hours)
    {
        $appendTo = '<w:t>Contact Hours</w:t></w:r></w:p></w:tc></w:tr>';
        $position = strpos($content, $appendTo);
        $rows = '';
        $totalHours = 0;
        foreach ($hours as $index => $hours) {
            $row = '<w:tr w:rsidR="008D0CEB" w:rsidRPr="003B6DF4" w14:paraId="5D8E1F98" w14:textId="77777777" w:rsidTr="0068281D"><w:trPr><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="841" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="364B369B" w14:textId="676922FF" w:rsidR="008D0CEB" w:rsidRPr="0068281D" w:rsidRDefault="0068281D" w:rsidP="0068281D"><w:pPr><w:ind w:left="300"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:rtl/><w:lang w:bidi="ar-EG"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:lang w:bidi="ar-EG"/></w:rPr><w:t>' . $index . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="6734" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="7E7AAFBF" w14:textId="1BBF17E0" w:rsidR="008D0CEB" w:rsidRPr="00DB0E18" w:rsidRDefault="0068281D" w:rsidP="00237363"><w:pPr><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/><w:rtl/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $hours->activity . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2022" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="43B5319D" w14:textId="1F7A87A2" w:rsidR="008D0CEB" w:rsidRPr="003B6DF4" w:rsidRDefault="0068281D" w:rsidP="00237363"><w:pPr><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:rtl/><w:lang w:bidi="ar-EG"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:lang w:bidi="ar-EG"/></w:rPr><w:t>' . $hours->hours . '</w:t></w:r></w:p></w:tc></w:tr>';
            $totalHours += $this->getAmount($hours->hours);

            $rows .= $row;
        }
        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        $content = $part1 . $rows . $part2;
        $content = str_replace('$total_contactHours', $totalHours, $content);
        return $content;
    }
    private function addRowsInstruction($content, $instructions, $preIndex)
    {
        $appendTo = '';

        switch ($preIndex) {
            case 1:
                $appendTo = '<w:t>Knowledge and understanding</w:t></w:r></w:p></w:tc></w:tr>';
                break;
            case 2:
                $appendTo = '<w:t>Skills</w:t></w:r></w:p></w:tc></w:tr>';
                break;
            case 3:
                $appendTo = '<w:t xml:space="preserve"> and responsibility</w:t></w:r></w:p></w:tc></w:tr>';
                break;
        }
        $position = strpos($content, $appendTo);
        $rows = '';
        foreach ($instructions as $index => $plo) {
            $row = '<w:tr w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w14:paraId="2E821FBF" w14:textId="77777777" w:rsidTr="00067A97"><w:trPr><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="901" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="20103120" w14:textId="77777777" w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w:rsidRDefault="006E3A65" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r w:rsidRPr="003B6DF4"><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . ($preIndex . '.' . ($index + 1)) . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2322" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="28395DEF" w14:textId="324785E5" w:rsidR="006E3A65" w:rsidRPr="003B0CC7" w:rsidRDefault="003B0CC7" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:t>' . ($plo->learning_outcome ?? "empty") . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2481" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="3BD57457" w14:textId="5B453D2D" w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w:rsidRDefault="003B0CC7" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:t>' . ($plo->CLO_code ?? "empty") . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2087" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="06DCF145" w14:textId="5BC6F84E" w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w:rsidRDefault="003B0CC7" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:t>' . ($plo->teaching_strategies ?? "empty") . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1757" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="6520985D" w14:textId="3992E0CF" w:rsidR="006E3A65" w:rsidRPr="003B6DF4" w:rsidRDefault="003B0CC7" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:t>' . ($plo->assessment_methods ?? "empty") . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr>';
            $rows .= $row;
        }
        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        $content = $part1 . $rows . $part2;
        return $content;
    }
    private function replaceRequirements($content, $placeholder, $requirements)
    {
        $replacement = '';

        foreach ($requirements as $index => $requirement) {
            $replacement .= $index + 1 . "-" . $requirement->name . "<w:br/>";
        }

        return str_replace($placeholder, $replacement, $content);
    }
    private function addRowsContent($content, $topics)
    {
        $appendTo = '<w:t>Contact Hours</w:t></w:r></w:p></w:tc></w:tr>';
        $position = strpos($content, $appendTo);
        $position = strpos($content, $appendTo, $position + strlen($appendTo));

        $rows = '';
        $totalHours = 0;
        foreach ($topics as $index => $topic) {
            $row = '<w:tr w:rsidR="00E064B0" w:rsidRPr="003B6DF4" w14:paraId="26CF14F5" w14:textId="77777777" w:rsidTr="00067A97"><w:trPr><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="572" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="0CACCAE9" w14:textId="01137DAF" w:rsidR="00652624" w:rsidRPr="00E847D9" w:rsidRDefault="00E847D9" w:rsidP="00E847D9"><w:pPr><w:spacing w:after="0"/><w:ind w:right="212"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . ($index + 1) . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="7215" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="5BF1116D" w14:textId="72DB86AD" w:rsidR="00652624" w:rsidRPr="003B6DF4" w:rsidRDefault="00E847D9" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $topic->topic . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1789" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="4A36790F" w14:textId="6EDE0A0C" w:rsidR="00652624" w:rsidRPr="003B6DF4" w:rsidRDefault="00E847D9" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $topic->contact_hours . '</w:t></w:r></w:p></w:tc></w:tr>';
            $totalHours += $this->getAmount($topic->contact_hours);
            $rows .= $row;
        }
        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        $content = $part1 . $rows . $part2;
        $content = str_replace('$total_content', $totalHours, $content);
        return $content;
    }
    private function addRowsAssissment($content, $assessments)
    {
        $appendTo = '<w:t>Percentage of Total Assessment Score</w:t></w:r></w:p></w:tc></w:tr>';
        $position = strpos($content, $appendTo);

        $rows = '';
        foreach ($assessments as $index => $assessment) {
            $row = '<w:tr w:rsidR="00E434B1" w:rsidRPr="003B6DF4" w14:paraId="76F00AF6" w14:textId="77777777" w:rsidTr="00067A97"><w:trPr><w:trHeight w:val="260"/><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="645" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="2637B181" w14:textId="2196A25E" w:rsidR="00E434B1" w:rsidRPr="004C3286" w:rsidRDefault="004C3286" w:rsidP="004C3286"><w:pPr><w:spacing w:after="0"/><w:ind w:right="212"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="000000" w:themeColor="text1"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="000000" w:themeColor="text1"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . ($index + 1) . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4787" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="41A17795" w14:textId="4D976F53" w:rsidR="00E434B1" w:rsidRPr="00067A97" w:rsidRDefault="004C3286" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="000000" w:themeColor="text1"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="000000" w:themeColor="text1"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $assessment->assessment_activity . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1520" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="6A75612C" w14:textId="0F6D26AB" w:rsidR="00E434B1" w:rsidRPr="00067A97" w:rsidRDefault="004C3286" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="000000" w:themeColor="text1"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="000000" w:themeColor="text1"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $assessment->assessment_timing . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2610" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="341DD137" w14:textId="7A905F4E" w:rsidR="00E434B1" w:rsidRPr="00067A97" w:rsidRDefault="004C3286" w:rsidP="00237363"><w:pPr><w:spacing w:after="0"/><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="000000" w:themeColor="text1"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="000000" w:themeColor="text1"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $assessment->percentage . '</w:t></w:r></w:p></w:tc></w:tr>';
            $rows .= $row;
        }
        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        $content = $part1 . $rows . $part2;
        return $content;
    }
    private function addRowsFacilities($content, $equipments)
    {
        $appendTo = '<w:t>Resources</w:t></w:r></w:p></w:tc></w:tr>';
        $position = strpos($content, $appendTo);

        $rows = '';
        foreach ($equipments as $index => $equipment) {
            $row = '<w:tr w:rsidR="00215895" w:rsidRPr="003B6DF4" w14:paraId="2CC0C5BC" w14:textId="77777777" w:rsidTr="00237363"><w:trPr><w:trHeight w:val="655"/><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="4605" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="22153A33" w14:textId="64E6CF69" w:rsidR="00215895" w:rsidRPr="003B6DF4" w:rsidRDefault="004C3286" w:rsidP="00237363"><w:pPr><w:spacing w:line="276" w:lineRule="auto"/><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:rtl/><w:lang w:bidi="ar-EG"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/><w:lang w:bidi="ar-EG"/></w:rPr><w:t>' . $equipment->items . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="4985" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/></w:tcPr><w:p w14:paraId="236675C5" w14:textId="26837B92" w:rsidR="00215895" w:rsidRPr="003B6DF4" w:rsidRDefault="004C3286" w:rsidP="004C3286"><w:pPr><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $equipment->resource . '</w:t></w:r></w:p></w:tc></w:tr>';
            $rows .= $row;
        }
        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        $content = $part1 . $rows . $part2;
        return $content;
    }
    private function addRowsAssessmentQuality($content, $assessments)
    {
        $appendTo = '<w:t>Methods</w:t></w:r></w:p></w:tc></w:tr>';
        $position = strpos($content, $appendTo);

        $rows = '';
        foreach ($assessments as $index => $assessment) {
            $row = '<w:tr w:rsidR="00844E6A" w:rsidRPr="003B6DF4" w14:paraId="0E232717" w14:textId="77777777" w:rsidTr="00067A97"><w:trPr><w:trHeight w:val="283"/><w:tblCellSpacing w:w="7" w:type="dxa"/><w:jc w:val="center"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="3732" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="3B93B49E" w14:textId="5EFBF172" w:rsidR="00844E6A" w:rsidRPr="003B6DF4" w:rsidRDefault="004C3286" w:rsidP="00237363"><w:pPr><w:jc w:val="center"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:lang w:bidi="ar-EG"/></w:rPr></w:pPr><w:bookmarkStart w:id="20" w:name="_Hlk513021635"/><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:lang w:bidi="ar-EG"/></w:rPr><w:t>' . $assessment->assessment_area . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="3065" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="2C84D7D5" w14:textId="186B2A50" w:rsidR="00844E6A" w:rsidRPr="003B6DF4" w:rsidRDefault="004C3286" w:rsidP="00237363"><w:pPr><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:rtl/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $assessment->assessor . '</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="2779" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F2F2F2" w:themeFill="background1" w:themeFillShade="F2"/><w:vAlign w:val="center"/></w:tcPr><w:p w14:paraId="7F58D653" w14:textId="4644931C" w:rsidR="00844E6A" w:rsidRPr="003B6DF4" w:rsidRDefault="004C3286" w:rsidP="00237363"><w:pPr><w:jc w:val="lowKashida"/><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/><w:rtl/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:rFonts w:cstheme="minorHAnsi"/><w:b/><w:bCs/><w:color w:val="525252" w:themeColor="accent3" w:themeShade="80"/><w:sz w:val="24"/><w:szCs w:val="24"/></w:rPr><w:t>' . $assessment->assesment_method . '</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr>';
            $rows .= $row;
        }
        $part1 = substr($content, 0, $position + strlen($appendTo));
        $part2 = substr($content, $position + strlen($appendTo));

        $content = $part1 . $rows . $part2;
        return $content;
    }
    function getAmount($hoursString)
    {
        $parts = explode('-', $hoursString);

        if (count($parts) !== 2) {
            // Incorrect format, return 0
            return 0;
        }

        list($start, $end) = $parts;

        $startComponents = explode(':', $start);
        $endComponents = explode(':', $end);

        if (count($startComponents) !== 2 || count($endComponents) !== 2) {
            // Incorrect time format, return 0
            return 0;
        }

        list($startHour, $startMinute) = $startComponents;
        list($endHour, $endMinute) = $endComponents;

        if (
            !ctype_digit($startHour) || !ctype_digit($startMinute) ||
            !ctype_digit($endHour) || !ctype_digit($endMinute)
        ) {
            // Invalid numeric values, return 0
            return 0;
        }

        $startTime = $startHour * 60 + $startMinute;
        $endTime = $endHour * 60 + $endMinute;

        if ($startTime > $endTime) {
            // End time is before start time, return 0
            return 0;
        }

        $amount = ($endTime - $startTime) / 60;

        return $amount;
    }
}
