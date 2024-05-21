<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VeterinaryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

Route::get("/user", function (Request $request) {
    return $request->user();
})->middleware("auth:sanctum");

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);

    Route::prefix("posts")->group(function () {
        Route::get("/", [PostController::class, "index"]);
        Route::get("/{id}", [PostController::class, "show"]);
        Route::post("/create", [PostController::class, "create"]);
        Route::put("/{id}/update", [PostController::class, "update"]);
        Route::delete("/{id}/destroy", [PostController::class, "delete"]);
    });

    Route::prefix("veterinaries")->group(function () {
        Route::get("/", [VeterinaryController::class, "index"]);
        Route::get("/{id}", [VeterinaryController::class, "show"]);
        Route::post("/create", [VeterinaryController::class, "create"]);
        Route::put("/{id}/update", [VeterinaryController::class, "update"]);
        Route::delete("/{id}/destroy", [VeterinaryController::class, "delete"]);
    });

    Route::prefix("reports")->group(function () {
        Route::get("/", [ReportController::class, "index"]);
        Route::get("/{id}", [ReportController::class, "show"]);
        Route::post("/create", [ReportController::class, "store"]);
        Route::put("/{id}/update", [ReportController::class, "update"]);
        Route::delete("/{id}/destroy", [ReportController::class, "delete"]);
    });

    Route::prefix("profile")->group(function () {
        Route::get("/", [ProfileController::class, "show"]);
        Route::post("/create", [ProfileController::class, "store"]);
        Route::put("/update-name", [ProfileController::class, "updateName"]);
        Route::put("/update-last-name", [ProfileController::class,"updateLastName"]);
        Route::put("/update-city", [ProfileController::class,"updateCity"]);
        Route::put("/update-profile-picture", [ProfileController::class,"updateProfilePicture"]);
        Route::put("/update-cover-picture", [ProfileController::class,"updateCoverPicture"]);
        Route::delete("/destroy", [ProfileController::class,"delete"]))
    });
});
