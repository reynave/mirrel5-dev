import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { ConfigService } from './../service/config.service';
import { Setting } from './setting';

@Component({
  selector: 'app-setting',
  templateUrl: './setting.component.html',
  styleUrls: ['./setting.component.css']
})
export class SettingComponent implements OnInit {

  loading: boolean = false;
  email : string;
  pass : string;
  oldPass : string;
  embed: any = {
    header: "",
    footer: "",
  }
  smtp:any = {
    smtp_host : "",
    smtp_port : "",
    smtp_user : "",
    smtp_pass : "",
    smtp_to : "",
    subject : ""
  }
  link: any = {
    sitemap: this.configService.base_url() + 'sitemap.xml',
    json: localStorage.getItem('mirrel5iframe') ? localStorage.getItem('mirrel5iframe')+"?data=json"  : this.configService.base_url() + "?data=json",
  }
  constructor(
    private http: HttpClient,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private configService: ConfigService,
  ) { }

  ngOnInit() {
    this.httpGet();
  }

  httpGet() {
    this.loading = true;
    var url = this.configService.api() + 'setting_load';

    this.http.get<Setting>(url, {
      headers: this.configService.headers()
    }).subscribe(data => {
      console.log(data);
      this.embed = {
        header: data['result']['embed']['header_code'],
        footer: data['result']['embed']['embed_code'],
      }
      this.smtp = {
        host: data['result']['smtp']['smtp_host'],
        pass: data['result']['smtp']['smtp_pass'],
        port: data['result']['smtp']['smtp_port'],
        to: data['result']['smtp']['smtp_to'],
        user: data['result']['smtp']['smtp_user'],
        subject: data['result']['smtp']['subject'],
      }
      this.email = data['result']['account']['email'];

    }, error => {
      console.log(error.error);
      console.log(error.error.text);
    });
  }

  updateEmbed() {
    var data = {
      header: btoa(this.embed['header']),
      footer: btoa(this.embed['footer']), 
    } 
    this.http.post(this.configService.api() + 'embedCode_update', {
      data: data,
    }, {
      headers: this.configService.headers()
    }).subscribe(data => {
      this.loading = false; 
      this.sendChild();
    },
      error => {
        console.log(error.error.text);
      }
    );
  }

  updateSmtp(){
    var data = {
      smtp_host: this.smtp['host'],
      smtp_pass: this.smtp['pass'],
      smtp_port: this.smtp['port'],
      smtp_to: this.smtp['to'],
      smtp_user: this.smtp['user'],
      subject: this.smtp['subject'],
      
    }  

    this.http.post(this.configService.api() + 'setting_smtp_update', {
      data: data,
    }, {
      headers: this.configService.headers()
    }).subscribe(data => {
      this.loading = false;
    },
      error => {
        console.log(error.error.text);
      }
    );
  }
  note:string;
  updateAcount(){ 
    this.http.post(this.configService.api() + 'setting_account_update', {
      email: this.email,
      password : this.pass,
      oldPass : this.oldPass,
    }, {
      headers: this.configService.headers()
    }).subscribe(data => {
      this.loading = false;
      console.log(data);
      this.note = data['note'];
    },
      error => {
        console.log(error.error.text);
      }
    );
  }

  
  sendChild() {
    console.log('widget.compontent : sendChild');
    var iframe = document.getElementById('iframe-live');
    if (iframe == null) return;
    var iWindow = (<HTMLIFrameElement>iframe).contentWindow;
    iWindow.postMessage({ "function": "refresh" }, '*');
  }

}
