import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http'; 
import { fn } from '@angular/compiler/src/output/output_ast';
import { ContentEdit } from './../content';
import { ConfigService } from './../../service/config.service';

@Component({
  selector: 'app-content-edit',
  templateUrl: './content-edit.component.html',
  styleUrls: ['./content-edit.component.css']
})
export class ContentEditComponent implements OnInit {
  loading: boolean = false;
  model: any = [];
  id:string;
  status : any; 
  selectedFile: File = null;
  img_path:string;
  note:string;
  parent_name:string;
  pages : any = []; 

  constructor(
    private location:Location,
    private http: HttpClient,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private configService : ConfigService
  ) { }

  ngOnInit() {
    this.id = this.activatedRoute.snapshot.params.id; 
    console.log(this.id);
    this.httpGet(this.id);
    console.log('API '+this.configService.api());
  }

  httpGet(id) { 
    this.loading = true;
    var url = this.configService.api()+'content_detail/' + id;
    console.log(url);
    this.http.get(url, {
         headers: this.configService.headers()
    }).subscribe(data => { 
      console.log(data);
      this.pages =  data['select']['pages'];
      this.status =  data['select']['status'];
      this.model =   new ContentEdit( 
        data['result']['data']['id_pages'],
        data['result']['data']['name'],
        data['result']['data']['h1'],
        data['result']['data']['h2'],
        data['result']['data']['h3'],
        data['result']['data']['img'],
        data['result']['data']['title'],
        data['result']['data']['metadata_description'],
        data['result']['data']['metadata_keywords'], 
        
        data['result']['data']['url'],
        data['result']['data']['status'],
      );
      this.img_path = data['result']['data']['img_path'];
    
      this.loading = false; 
 

    }, error => {
      console.log(error.error);
      console.log(error.error.text);
    });
  }

  refresh:boolean=false;
  onSubmit() { 
    console.log(this.model);
    console.log(this.refresh);
    this.http.post( this.configService.api()+'content_update',{ 
      id :this.id,
      data: this.model
    }, {
      headers: this.configService.headers()
    }).subscribe(data => { 
      this.loading = false;  
      if(data['error'] == 0){ 
        this.note = "update done";
        console.log(data);
        this.sendChild(data['result']);
      }
    },
      error => {
        console.log(error.error.text);
      }
    );

  }



  sendChild(url) {
    console.log('content.compontent : sendChild'); 
    var iframe = document.getElementById('iframe-live');
    if (iframe == null) return;
    var iWindow = (<HTMLIFrameElement>iframe).contentWindow;
    localStorage.setItem('mirrel5iframe',url);
    iWindow.postMessage({ "function":"redirect", "data": url }, '*');
  }






  url_title(){
    this.model['url'] = this.model['url'].replace(/\s+/g, '-').toLowerCase(); 
  }
  fnURL(){
    this.model['url'] = this.model['name'].replace(/\s+/g, '-').toLowerCase() + '.html'; 
    this.model['title'] = this.model['name'];
  }

  back(){
    this.router.navigate(['pages/',this.id]);
    //this.location.back();
  }


  upload:string;
  onFileSelected(event) { 
    if( <File>event.target.files[0] ){

    
    this.upload = 'uploading...';
    this.model['img'] = this.configService.base_url()+'admin/img/Ripple-1s-200px.svg';
    this.selectedFile = <File>event.target.files[0];
    const fd = new FormData();
    fd.append('images', this.selectedFile, this.selectedFile.name);
    fd.append('id', this.id);

    this.http.post( this.configService.api()+'content_upload/', fd,{
        headers: this.configService.headers()
    })
      .subscribe(res => {
        if (res['error']) {
          alert(res['error']);
        } else {
         this.model['img'] = res['img']; 
        } 
        this.upload = '';
      },
        error => { console.log(error); }
      );
    }
  }

}
