import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http'; 
import { fn } from '@angular/compiler/src/output/output_ast';
import { PagesDetail, PagesEdit } from './../pages';
import { ConfigService } from './../../service/config.service';

@Component({
  selector: 'app-pages-edit',
  templateUrl: './pages-edit.component.html',
  styleUrls: ['./pages-edit.component.css']
})
export class PagesEditComponent implements OnInit {
  loading: boolean = false;
  model: any = [];
  id:string;
  select : any;
  id_pages:string;
  selectedFile: File = null;
  img_path:string;
  note:string;
  parent_name:string;

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
    var url = this.configService.api()+'pages_detail/' + id;
    console.log(url);
    this.http.get<PagesDetail>(url, {
         headers: this.configService.headers()
    }).subscribe(data => { 
      this.id_pages = data['result']['data']['id_pages'];
      this.parent_name = data['result']['data']['parent_name'];
      
      this.model =   new PagesEdit(
        data['result']['data']['id_pages'],
        data['result']['data']['name'],
        data['result']['data']['url'],
        data['result']['data']['href'],
        data['result']['data']['href_target_blank'],
        data['result']['data']['img'],
        data['result']['data']['title'],
        data['result']['data']['metadata_description'],
        data['result']['data']['metadata_keywords'],
        data['result']['data']['post'],
        data['result']['data']['themes'],
        
      );
      this.img_path = data['result']['data']['img_path'];
      this.select = data['result']['themes'];
      this.loading = false; 
 

    }, error => {
      console.log(error.error);
      console.log(error.error.text);
    });
  }

  onSubmit() { 
    console.log(this.model);
   
    this.http.post( this.configService.api()+'pages_update',{ 
      id :this.id,
      data: this.model
    }, {
      headers: this.configService.headers()
    }).subscribe(data => { 
      this.loading = false;  
      if(data['error'] == 0){
        this.sendChild();
        this.router.navigate(['pages/',this.id_pages]);
        this.note = "update done";
      }
    },
      error => {
        console.log(error.error.text);
      }
    );

  }
  url_title(){
    this.model['url'] = this.model['url'].replace(/\s+/g, '-').toLowerCase(); 
  }
  fnURL(){
    this.model['url'] = this.model['name'].replace(/\s+/g, '-').toLowerCase(); 
    this.model['title'] = this.model['name'];
  }

  back(){
    this.router.navigate(['pages/',this.id_pages]);
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

    this.http.post( this.configService.api()+'pages_upload/', fd,{
        headers: this.configService.headers()
    })
      .subscribe(res => {
        if (res['error']) {
          alert(res['error']);
        } else {
         this.model['img'] = res['img']; 
         this.sendChild();
        } 
        this.upload = '';
      },
        error => { console.log(error); }
      );
    }
  }


  sendChild() {
    console.log('widget.compontent : sendChild');
    var iframe = document.getElementById('iframe-live');
    if (iframe == null) return;
    var iWindow = (<HTMLIFrameElement>iframe).contentWindow;
    iWindow.postMessage({ "function": "refresh" }, '*');
  }

}
