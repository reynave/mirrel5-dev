
import { Component, OnInit, HostListener, ViewChild } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { NgbModal, ModalDismissReasons, NgbModalConfig } from '@ng-bootstrap/ng-bootstrap';
import { Router, ActivatedRoute } from '@angular/router';
import { Location } from '@angular/common';
import { ConfigService } from "./service/config.service";

declare var window: any;
declare var $: any;

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent implements OnInit {
  title: string;
  login: boolean = false;
  closeResult: string;
  iframe: any;
  iframeSwitch: string = 'iframe-pc';
  iframeHeight: number;
  headerHeight: number = 28;
  mirrelIframe: string;
  brand: string = "Dev 4 Oct'19";
  setting: any = {
    header: true
  }


  @HostListener("window:message", ["$event"])
  parentReceive($event: MessageEvent) {
  //  console.log($event.data);
    //window.location.hash = "/"+$event.data['data']['json']['table']+"/"+$event.data['data']['json']['id'];
    //this.activatedRoute.url.subscribe(url => console.log("fnUpdateUrl"));
   
   if ($event.data['function'] == 'fnUpdateUrl') {
      localStorage.setItem('mirrel5iframe', $event.data['data']);
      this.mirrelIframe = $event.data['data'];
    }
    if ($event.data['function'] == 'fnModal') {
      this.openModal($event.data['data']);
   
     /* if (JSON.stringify( $event.data['show'] )) {
        localStorage.setItem( $event.data['show']['section'], JSON.stringify($event.data['show']['label']['show']) );
      }*/
    }

    if ($event.data['function'] == 'fnRouter') {
      this.title = $event.data['data']['json']['title'];
      this.open(this.content, $event.data['data']['json']['router']);
    }

  }
  @ViewChild('content', { static: true }) private content;

  constructor(
    private activatedRoute: ActivatedRoute,
    private router: Router,
    config: NgbModalConfig,
    private modalService: NgbModal,
    private sanitizer: DomSanitizer,
    private location: Location,
    private configService: ConfigService
  ) {
    config.backdrop = 'static';
    config.keyboard = false;

  }
  base_url: string="";
  ngOnInit() {
    this.iframeHeight = window.innerHeight - this.headerHeight;
    this.reload();
    this.checkLogin(); 
    //this.open(this.content, 'content');
    this.base_url = this.configService.base_url();

  }

 


  checkLogin() {
    if (this.configService.checkLogin()) {
      this.login = true;
    } else {
      location.href = this.configService.base_url();
    }

  }

  open(content, nav) {
    this.router.navigate([nav]);
    this.modalService.open(content, { size: 'xl', ariaLabelledBy: 'modal-basic-title' }).result.then((result) => {
      this.closeResult = `Closed with: ${result}`;
    }, (reason) => {
      this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
    });
  }

  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return `with: ${reason}`;
    }
  }


  reload() {
    if (localStorage.getItem('mirrel5iframe')) {
      this.mirrelIframe = localStorage.getItem('mirrel5iframe');
      this.iframe = this.sanitizer.bypassSecurityTrustResourceUrl(localStorage.getItem('mirrel5iframe'));
    } else {
      localStorage.setItem('mirrel5iframe', this.configService.base_url());
      this.mirrelIframe = this.configService.base_url();
      this.iframe = this.sanitizer.bypassSecurityTrustResourceUrl(this.configService.base_url());

    }

  }

  switch(select) {
    this.iframeSwitch = select;
  }

  toggleHeader() {
    var self = this;

    if (this.setting['header'] == true) {
      self.setting['header'] = false;
      $('.showTop').show();
      $('#header').slideUp("slow", function () {
        self.headerHeight = 0;
        self.iframeHeight = window.innerHeight;
      });
    } else {
      self.setting['header'] = true;
      self.headerHeight = 28;
      self.iframeHeight = window.innerHeight - self.headerHeight;
      $('.showTop').hide();
      $('#header').slideDown("slow", function () {

      });
    }


  }

  preview() {
    window.open(localStorage.getItem('mirrel5iframe'), '_blank')
  }


  openModal(data) {
    this.title = data['json']['title'];
    this.open(this.content, data['json']['table'] + '/' + data['json']['id']);
  }

  logout() {
    if (localStorage.getItem('mirrel5iframe')) {
      document.cookie = "mirrel5Login=''; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      location.href = localStorage.getItem('mirrel5iframe');
    } else {
      location.href = this.configService.base_url();
    }
  }
}
