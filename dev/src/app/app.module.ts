import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';

import { FormsModule }   from '@angular/forms';
import { HttpModule } from '@angular/http';
import { HttpClientModule } from '@angular/common/http';
import { EditorModule } from '@tinymce/tinymce-angular';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HomeComponent } from './home/home.component';
import { PageNotFoundComponentComponent } from './page-not-found-component/page-not-found-component.component';
import { PagesComponent } from './pages/pages.component';
import { PagesEditComponent } from './pages/pages-edit/pages-edit.component';
import { SettingComponent } from './setting/setting.component';
import { ContentComponent } from './content/content.component';
import { ContentEditComponent } from './content/content-edit/content-edit.component';
import { WidgetComponent } from './widget/widget.component';
import { WidgetEditComponent } from './widget/widget-edit/widget-edit.component';
import { WidgetSectionComponent } from './widget/widget-section/widget-section.component';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    PageNotFoundComponentComponent,
    PagesComponent,
    PagesEditComponent,
    SettingComponent,
    ContentComponent,
    ContentEditComponent,
    WidgetComponent,
    WidgetEditComponent,
    WidgetSectionComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    NgbModule,
    FormsModule,
    HttpModule,
    HttpClientModule,
    EditorModule 
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
