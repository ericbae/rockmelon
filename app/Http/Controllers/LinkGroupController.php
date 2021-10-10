<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use DB, Auth, Mail;
use Illuminate\Support\Facades\Http;
use App\Models\LinkGroup;
use App\Models\Link;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;

class LinkGroupController extends Controller {

  // public function __construct() {
  //   $this->emailService       = resolve('EmailService');
  //   $this->fileService        = resolve('FileService');
  //   $this->activityLogService = resolve('ActivityLogService');
  // }

  public function getTry() {
    return view('link-group/try');
  }

  public function getCreate() {
    return view('link-group/create');
  }

  public function postTry() {
    $lg             = new LinkGroup;
    $lg->timezone   = request()->timezone;
    $lg->ip         = request()->ip();
    $lg->name       = request()->name;
    $lg->identifier = Helper::getIdentifier();
    $lg->user_id    = auth()->check() ? auth()->id() : null;
    $lg->save();

    foreach(request()->links as $link) {
      $l                = new Link;
      $l->url           = $link;
      $l->link_group_id = $lg->id;
      $l->save();
    }

    return response()->json($lg);
  }

  public function postCreateViaApi() {
    $apiKey = request()->api_key;

    $user = User::where('api_key', $apiKey)->first();

    if($user) {
      $lg             = new LinkGroup;
      $lg->name       = request()->name;
      $lg->identifier = Helper::getIdentifier();
      $lg->user_id    = $user->id;
      $lg->save();

      if(request()->filled('urls')) {
        foreach(request()->urls as $link) {
          $l                = new Link;
          $l->url           = $link;
          $l->link_group_id = $lg->id;
          $l->save();
        }
      }

      return response()->json([
        'linkGroup' => [
          'identifier'   => $lg->identifier,
          'linkGroupUrl' => env('APP_URL') . '/rl/' . $lg->identifier,
          'name'         => $lg->name,
          'urls'         => request()->links
        ]
      ]);
    }
  }

  public function getLinkGroup($identifier) {
    $lg = LinkGroup::where('identifier', $identifier)->first();

    if($lg) {
      $lg->links = Link::where('link_group_id', $lg->id)->get();

      return view('link-group/view', [
        'linkGroup' => $lg,
      ]);
    }
  }

  public function getData() {
    $params = [];
    $q = "SELECT lg.*, l.num_links FROM link_groups lg 
          LEFT JOIN
          (
            SELECT COUNT(id) AS num_links, link_group_id FROM links GROUP BY link_group_id
          ) l ON l.link_group_id=lg.id
          WHERE user_id=? ORDER BY lg.id DESC";
    $params[] = auth()->id();
    $data = DB::select($q, $params);
    return response()->json($data);
  }

  public function getProductHunt() {
    $url   = 'https://api.producthunt.com/v1/';
    $token = 'bc040ff2690b43a3d8db65f9e6581dbc6b70d3a9c505935ab77c133bbcdad102';

    $res = Http::withHeaders([
      'accept'        => 'application/json',
      'content-type'  => 'application/json',
      'Authorization' => 'Bearer ' . $token
    ])->get($url . '/posts');    

    $data = json_decode($res->body(), true);

    $lg             = new LinkGroup;
    $lg->identifier = Helper::getIdentifier();
    $lg->user_id    = 1;
    $lg->save();

    foreach($data['posts'] as $post) {
      echo $post['redirect_url'];

      $l                = new Link;
      $l->url           = $post['redirect_url'];
      $l->link_group_id = $lg->id;
      $l->save();
    }
  }

}