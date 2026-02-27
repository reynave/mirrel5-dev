import { NgModule } from '@angular/core';
import { Routes, RouterModule, ActivatedRouteSnapshot } from '@angular/router';
import { PageNotFoundComponentComponent } from './page-not-found-component/page-not-found-component.component';
import { PagesComponent } from './pages/pages.component';
import { PagesEditComponent } from './pages/pages-edit/pages-edit.component';
import { SettingComponent } from './setting/setting.component';
import { ActiveGuardGuard } from './guard/active-guard.guard';
import { HomeComponent } from './home/home.component';
import { ContentEditComponent } from './content/content-edit/content-edit.component';
import { ContentComponent } from './content/content.component';
import { WidgetEditComponent } from './widget/widget-edit/widget-edit.component';
import { WidgetComponent } from './widget/widget.component';
import { WidgetSectionComponent } from './widget/widget-section/widget-section.component';


const routes: Routes = [
  { path: '', component: HomeComponent,  },
  { path: 'setting', component: SettingComponent, canActivate: [ActiveGuardGuard] },
  { path: 'pages', component: PagesComponent, canActivate: [ActiveGuardGuard] },
  {
    path: 'pages/:id', component: PagesComponent, canActivate: [ActiveGuardGuard],
    runGuardsAndResolvers:  (from: ActivatedRouteSnapshot, to: ActivatedRouteSnapshot) => {
      return false;
    }
  },
  {
    path: 'pages/edit/:id', component: PagesEditComponent, canActivate: [ActiveGuardGuard],
    runGuardsAndResolvers:  (from: ActivatedRouteSnapshot, to: ActivatedRouteSnapshot) => {
      return false;
    }
  },


  { path: 'widget', component: WidgetComponent, canActivate: [ActiveGuardGuard], },
  {
    path: 'widget/section/:section', component: WidgetSectionComponent, canActivate: [ActiveGuardGuard],
    runGuardsAndResolvers:  (from: ActivatedRouteSnapshot, to: ActivatedRouteSnapshot) => {
      return false;
    }
  },
  {
    path: 'widget/:id', component: WidgetEditComponent, canActivate: [ActiveGuardGuard], pathMatch: 'full',
    runGuardsAndResolvers:  (from: ActivatedRouteSnapshot, to: ActivatedRouteSnapshot) => {
      return false;
    }
  },


  { path: 'content', component: ContentComponent, canActivate: [ActiveGuardGuard] },
  {
    path: 'content/:id', component: ContentEditComponent, canActivate: [ActiveGuardGuard], pathMatch: 'full',
    runGuardsAndResolvers:  (from: ActivatedRouteSnapshot, to: ActivatedRouteSnapshot) => {
      return false;
    }
  
  },

  { path: '**', component: PageNotFoundComponentComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
