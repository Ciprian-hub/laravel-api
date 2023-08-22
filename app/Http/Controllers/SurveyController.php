<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use http\Env\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //test
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id', $user->id))->paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyRequest $request)
    {
        $data = $request->validated();

        if(isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }

        $survey = Survey::create($data);

        return new SurveyResource($survey);
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey, Request $request)
    {
        $user = $request->user();
        if ($user->id !== $survey->user_id) {
                return abort(403, 'Unauthorized action.');
        };

        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        $data = $request->validated();

        if(isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }

        if($survey->image) {
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
        }


        $survey->update($data);
        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey, Request $request)
    {
        $user = $request->user();
        if ($user->id === $survey->user_id) {
            return abort(403, 'Unauthorized action.');
        }

        $survey->delete();
        return response(" ", 204);
    }

    private function saveImage(mixed $image)
    {
        //Check if image is valid base64 string

        if(preg_match('/^data:image\/(\w+);base64,/', $image, $type)){
            $image = substr($image, strpos($image, ',') +1);

            //Get file extension
            $type = strtolower($type[1]);

            //Check the type
            if(!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new \Exception("invalid image type");
            }
            $image = str_replace(" ", "+", $image);
            $image = base64_decode($image);

            if($image === false) {
                throw new \Exception("base64_decode failed");
            }

        } else {
            throw new \Exception("image URI error");
        }
        $dir = 'images/';
        $file = Str::random() . "." . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;

        if(!File::exists($dir)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);
        return $relativePath;
    }
}
