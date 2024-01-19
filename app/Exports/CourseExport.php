<?php

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Element\Control;
use App\Http\Models\Course;
use Carbon\Carbon;

class WordDocumentController extends Controller
{
    public $course;
    public $phpWord;
    public function export($id)
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
        if ($this->course)
            return view('404');

        $this->phpWord = IOFactory::load(storage_path('app/private/course-template.docx'));
        $this->PopulateFirstPage();

        $this->phpWord->saveAs(storage_path("app/private/courses/course-$this->course->id.docx"));

        return response()->download(storage_path("app/private/courses/course-$this->course->id.docx"));
    }
    private function PopulateFirstPage()
    {
        $lastRevisionDate = Carbon::parse(json_decode($this->course->last_revision)->date)->format('Y/m/d');

        $this->replaceTextInContentControlElement('Enter Course Title.', $this->course->title);
        $this->replaceTextInContentControlElement('Enter Course Code.', $this->course->code);
        $this->replaceTextInContentControlElement('Enter Program Name.', $this->course->program);
        $this->replaceTextInContentControlElement('Enter Department Name .', $this->course->department->name);
        $this->replaceTextInContentControlElement('Enter College Name.', $this->course->college->name);
        $this->replaceTextInContentControlElement('Enter Institution Name.', $this->course->institution);
        $this->replaceTextInContentControlElement('Course Specification Version Number ', $this->course->version);
        $this->replaceTextInContentControlElement('Pick Revision Date.', $lastRevisionDate);
    }
    private function replaceTextInContentControlElement($search, $replace)
    {
        foreach ($this->phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof Control) {
                    if (strpos($element->getText(), $search) !== false) {
                        $element->setText(str_replace($search, $replace, $element->getText()));
                    }
                }
            }
        }
    }
}
