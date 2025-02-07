import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ActualizarContactoPage } from './actualizar-contacto.page';

describe('ActualizarContactoPage', () => {
  let component: ActualizarContactoPage;
  let fixture: ComponentFixture<ActualizarContactoPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ActualizarContactoPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
