import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { ConfigService } from './../service/config.service';
import { ContentList } from './content';
 
@Component({
  selector: 'app-content',
  templateUrl: './content.component.html',
  styleUrls: ['./content.component.css']
})
export class ContentComponent implements OnInit {
  loading: boolean = false;
  items: any = [];
  no: number = 0;
  order:any = {
    no : "",
    total : ""

  }

  constructor(
    private http: HttpClient,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private configService : ConfigService,
  ) { }

  ngOnInit() {
    this.httpGet(this.no);
  }

  httpGet(no) {
 
    this.loading = true;
    var url = this.configService.api()+'content_index/' + no;
    this.http.get<ContentList>(url, {
         headers: this.configService.headers()
    }).subscribe(data => {
      console.log(data) 
      this.items = data['result'];
      this.order = data['order'];
      
      this.loading = false; 
    }, error => {
      console.log(error.error);
      console.log(error.error.text);
    });
  }

  redirect(url){ 
    console.log('content.compontent : sendChild'); 
    var iframe = document.getElementById('iframe-live');
    if (iframe == null) return;
    var iWindow = (<HTMLIFrameElement>iframe).contentWindow;
    localStorage.setItem('mirrel5iframe',url);
    iWindow.postMessage({ "function":"redirect", "data": url }, '*');
  }

}
