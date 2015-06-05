<?php

class AttachmentController extends \BaseController {

	
	public function index($departmentId, $subjectId, $questionId)
	{
		$attachments = Department::find($departmentId)->subjects()->find($subjectId)->questions()->find($questionId)->attachments;
		return Response::json($attachments);
	}

	
	public function store($departmentId, $subjectId, $questionId)
	{
		$details = Input::all();
		$details['question_id'] = $questionId;

		if(Input::hasFile('attachment'))
		{
			$file = Input::file('attachment');
	        $extension = $file->getClientOriginalExtension();
	        $fileName = $questionId . '_' . str_random(16) . '.' . $extension;
	        $destinationPath = 'uploads/attachments';
	        $details['path'] = $fileName;
	        if(($file->move($destinationPath, $fileName)))
	        {
	        	if(Attachment::create($details))
	        	{
	        		return Response::json(['alert' => 'Sucessfully uploaded attachment']);
	        	}
	        	else
	        	{
	        		File::delete($destinationPath.$fileName);
	        		return Response::json(['alert' => 'Failed to upload attachment']);
	        	}
	        }
	        else
	        {
	        	return Response::json(['alert' => 'Failed to upload attachment']);
	        }
		}
		else
		{
			return Response::json(['alert' => 'No attachment found']);
		}
	}

	
	public function update($departmentId, $subjectId, $questionId, $attachmentId)
	{
		$attachment = Department::find($departmentId)->subjects()->find($subjectId)->questions()->find($questionId)->attachments()->find($attachmentId);
		$oldAttachment = $attachment;
		$destinationPath = 'uploads/attachments';

		if($attachment)
		{
			if(Input::hasFile('attachment'))
			{
				$newFile = Input::file('attachment');
		        $extension = $newFile->getClientOriginalExtension();
		        $newFileName = $questionId . '_' . str_random(16) . '.' . $extension;
		        $details['path'] = $newFileName;
		        if(($newFile->move($destinationPath, $newFileName)))
		        {
		        	if($attachment->update($details))
		        	{
		        		File::delete($destinationPath.$oldAttachment->path);
		        		return Response::json(['alert' => 'Sucessfully uploaded attachment']);
		        	}
		        	else
		        	{
		        		File::delete($destinationPath.$newFileName);
		        		return Response::json(['alert' => 'Failed to update attachment']);
		        	}
		        }
		        else
		        {
		        	return Response::json(['alert' => 'Failed to upload attachment']);
		        }
			}
		}
	}

	
	public function destroy($departmentId, $subjectId, $questionId, $attachmentId)
	{
		$attachment = Department::find($departmentId)->subjects()->find($subjectId)->questions()->find($questionId)->attachments()->find($attachmentId);

		if($attachment)
		{
			$destinationPath = 'uploads/attachments/';
			$fileName = $destinationPath . $attachment->path;
			if($attachment->delete())
			{
				File::delete($fileName);
				return Response::json(['alert' => 'Successfully deleted attachment']);
			}
			else
			{
				return Response::json(['alert' => 'Failed to delete attachment']);
			}
		}
		else
		{
			return Response::json(['alert' => 'Attachment not found']);
		}
	}
}