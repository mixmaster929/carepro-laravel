<?php
namespace App\Lib;

use App\CandidateFieldGroup;
use App\User;
use Illuminate\Support\Facades\App;

class ProfileGenerator
{
    private $stack = array();

    public function getHtml($id,$full=false)
    {
        $user = User::find($id);
        if($full){
            $groups = CandidateFieldGroup::orderBy('sort_order')->get();
        }
        else{
            $groups = CandidateFieldGroup::where('visible',1)->orderBy('sort_order')->get();
        }
        $html = view('admin.pdf.profile', compact('user','full','groups'))->render();
        return $html;
    }

    public function createPdf($id,$save=true,$full=false,$savePath='./')
    {
        $user = User::find($id);

        if(!$user->candidate){
            return false;
        }

        $name = $user->candidate->display_name;
        $html= $this->getHtml($id,$full);


        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html)->setPaper('a4', 'portrait');


        if ($full)
        {
            $type='full';
        }
        else
        {
            $type ='partial';
        }

        $fileName= safeUrl(__('site.candidate-resume')).'_'.$id.'_'.$this->sanitize_file_name($name)."_$type".'.pdf';
        if ($save)
        {
            $fileName = $savePath.$fileName;
            $pdf->save($fileName);
            $this->addToStack($fileName);
        }
        else
        {

            return $pdf->download($fileName);
        }


        return $fileName;


    }

    public function createInvoice($name,$address,$price,$description)
    {
        $html = view('admin.pdf.invoice', compact('name','address','price','description'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

        $fileName = setting('general_site_name').'_invoice_'.$this->sanitize_file_name($name).'.pdf';

        $pdf->save($fileName);
        $this->addToStack($fileName);
        return $fileName;


    }

    public function addToStack($file)
    {
        $this->stack[] = $file;
    }

    /**
     * Deletes items on stack
     */
    public function deleteStack()
    {
        foreach($this->stack as $value)
        {
            unlink($value);
        }
    }

    public function getStack()
    {
        return $this->stack;
    }

    public function sanitize_file_name( $filename ) {

        return strtolower(preg_replace("[^\w\s\d\.\-_~,;:\[\]\(\]]", '', $filename));

    }


}