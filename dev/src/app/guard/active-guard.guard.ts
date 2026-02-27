import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree } from '@angular/router';
import { ConfigService } from '../service/config.service';

@Injectable({
  providedIn: 'root'
})
export class ActiveGuardGuard implements CanActivate {
  constructor(
    private router: Router,
    private configService: ConfigService
  ) { }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
  
    if (this.configService.token()) {
      console.warn("LOGGIN");
      return true;

    } else {
      console.warn("YOU ARE NOT LOGGED!");  
      return false;
    }

  }
  
}
