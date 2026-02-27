import { Injectable } from '@angular/core';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { Location } from '@angular/common';
 
declare var base_url: string;
declare var api:string;

@Injectable({
  providedIn: 'root'
})
export class ConfigService {
  varToken: string = this.getCookie('mirrel5Login');
  key: string = "12ul3Xz8WI-hHeh06cD1Z-Wo6TouEHuh-VIhcIbTEV4-LJkrGOm2Pq-0OUMS6bSDJ-dqv5GRR9Iy-44djYFZREp-Ys7egftRwL-YzfwnDlYqR";
  varHeaders: any = [];

  constructor(
    private http: HttpClient,
    private location: Location
  ) { }

  base_url() {
    return base_url;
  }
  api() {
    return api;
  }

  checkLogin(){
    this.reloadToken();
    return  this.getCookie('mirrel5Login');
  }

  reloadToken() {
    if(!this.getCookie('mirrel5Login') ){
      this.varToken = "no have token";
    }else{
      this.varToken = this.getCookie('mirrel5Login');
    }
  }

  headers() {
    this.reloadToken();

    return this.varHeaders = new HttpHeaders({
      'Accept': 'application/json',
      'Key': this.key,
      'Token': this.varToken,
    });
  }

  token() {
    return this.getCookie('mirrel5Login');
  }

  getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }
}
