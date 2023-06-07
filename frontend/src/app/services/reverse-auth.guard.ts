import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot, UrlTree } from '@angular/router';
import { Observable, map, take } from 'rxjs';
import { UserServiceService } from './user-service.service';

@Injectable({
  providedIn: 'root'
})
export class ReverseAuthGuard implements CanActivate {
  constructor(private userService: UserServiceService, private router: Router){}
  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): Observable<boolean | UrlTree> | Promise<boolean | UrlTree> | boolean | UrlTree {
    return this.userService.status().pipe(take(1), map((isLogged: boolean) => {
      if(isLogged){
        this.router.navigate(['/dashboard'])
        return false
      }else{
        return true
      }
    }))
  }

}
